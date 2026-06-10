<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_POST['hidebal']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Location: index.php");
    exit;
}

session_start();
$con = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

$sql = $con->prepare("SELECT * FROM `user` WHERE auth_token = ?");
$sql->bind_param('s', $_COOKIE['user']);
$sql->execute();

$result = $sql->get_result();
$row = mysqli_fetch_assoc($result);
if ($row === NULL || isset($_POST['logout'])) {
    $token = $_COOKIE["user"];
	setcookie("user", $token, time() - 3600, "/");
    header("Location: about.php");
    exit;
}
$USER_ID = $row['USER_ID'];
$HEX_USER_ID = substr("000000", strlen(dechex($USER_ID))) . dechex($USER_ID);
$USER_NAME = htmlspecialchars($row['name']);
$USER_ROLE = $row['role'];
$USER_BALANCE = $row['balance'];
$USER_EMAIL = $row['email'];
$USER_PASSWORD = $row['password'];
$USER_BALANCE_FORMATED = number_format($USER_BALANCE,'2',',','.');
$DATE_CREATED = $row['date_created'];
$TODAY = date('Y-m-d');

function verifyWID($ID) {
    global $con, $USER_ID, $msg;
    $WLID = intval($ID);
    $result = mysqli_query($con, "SELECT * FROM wishlist WHERE WISHLIST_ID = $WLID AND USER_ID = $USER_ID");
    if (mysqli_num_rows($result) == 0) {
        $msg = "<h3>WARNING!</h3> Unauthorized";
        return false;
    } else return true;
}

if (isset($_POST['addTR'])) {
    $date = $_POST['addTR']['date'];
    $amount = $_POST['addTR']['amount'];
    $note = $_POST['addTR']['note'];
    if ($_POST['addTR']['type'] == 2) $amount *= -1;

    $sql = $con->prepare("UPDATE `user` SET balance = balance + ? WHERE USER_ID = ?");
    $sql->bind_param('ii', $amount, $USER_ID);
    $sql->execute();

    $sql = $con->prepare("INSERT INTO transaction_log (USER_ID, date, amount, note)
    VALUES (?, ?, ?, ?)");
    $sql->bind_param('isis', $USER_ID, $date, $amount, $note);
    $sql->execute();

    $msg = '<h3>Notification!</h3>Transaction Added!';
}

if (isset($_POST['addWL'])) {
    $name = $_POST['addWL']['name'];
    $price = $_POST['addWL']['price'];
    $priority = $_POST['addWL']['priority'];
    $target = $_POST['addWL']['target'] == '' ? '9999-12-31' : $_POST['addWL']['target'];
    $goal = isset($_POST['addWL']['goal']) ? '1' : '0';

    if (isset($_POST['addWL']['goal'])) {
        $sql = "UPDATE wishlist SET goal = '0' WHERE USER_ID = $USER_ID";
        $result = mysqli_query($con, $sql);
    } 

    $sql = $con->prepare("INSERT INTO wishlist (USER_ID, name, price, priority, target, goal)
    VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param('isissi', $USER_ID, $name, $price, $priority, $target, $goal);
    $sql->execute();

    if ($priority == 'unset') $msg= "<h3>Notification!</h3>An Item Has Been Added to Wishlist!";
    else $msg = "<h3>Notification!</h3>A ".ucwords($priority)." Priority Item Has Been Added to Wishlist!";
}

if (isset($_POST['unmark']) || isset($_POST['mark'])) {
    $sql = "UPDATE wishlist SET goal = '0' WHERE USER_ID = $USER_ID";
    mysqli_query($con, $sql);
    $msg = "<h3>Notification!</h3>Your Item Has Been Unmarked Successfuly";
    
    if (isset($_POST['mark']) && verifyWID($_POST['mark'])) {
        $sql = "UPDATE wishlist SET goal = '1' WHERE WISHLIST_ID=".$_POST['mark'];
        mysqli_query($con, $sql);
        $msg = "<h3>Notification!</h3>Your Item Has Been Marked Successfuly";
    }
}

if (isset($_POST['deleteWL']) && verifyWID($_POST['deleteWL'])) {
    $sql = "DELETE FROM wishlist WHERE WISHLIST_ID=".$_POST['deleteWL'];
    mysqli_query($con, $sql);
    $msg = "<h3>Notification!</h3>Item Has Been Deleted Successfuly!";
}

if (isset($_POST['editWL']) && verifyWID($_POST['editWL']['id'])) {
    $name = $_POST['editWL']['name'];
    $price = intval($_POST['editWL']['price']);
    $priority = $_POST['editWL']['priority'];
    $target = $_POST['editWL']['target'] == '' ? '9999-12-31' : $_POST['editWL']['target'];
    
    $sql = $con->prepare("UPDATE wishlist SET name = ?, price = ?, priority = ?, target = ? WHERE WISHLIST_ID=".$_POST['editWL']['id']);
    $sql->bind_param('siss', $name, $price, $priority, $target);
    $sql->execute();
    
    $msg = "<h3>Notification!</h3>Wish Data Has Been Updated";
}

if (isset($_POST['fulfilWL']) && verifyWID($_POST['fulfilWL']['id'])) {
    $WLID = $_POST['fulfilWL']['id'];
    $WLname = "Fulfiled Wish \"".$_POST['fulfilWL']['name']."\"";
    $WLprice = -1 * $_POST['fulfilWL']['price'];

    $sql = "UPDATE wishlist SET fulfilled = 1, date_fulfilled = '$TODAY' WHERE WISHLIST_ID = $WLID";
    mysqli_query($con, $sql);

    $sql = $con->prepare("INSERT INTO transaction_log (USER_ID, date, amount, note) VALUES (?, ?, ?, ?)");
    $sql->bind_param('isss', $USER_ID, $TODAY, $WLprice, $WLname);
    $sql->execute();

    $sql = "UPDATE user SET balance = balance + $WLprice WHERE USER_ID = $USER_ID";
    mysqli_query($con,$sql);

    $msg = "<h3>Congratulations!</h3> Your Wish Has Been Fulfilled!";
}

function get_transaction($limit, $offset, $order, $filter) {
    global $con, $USER_ID;
    $count = 1;
    $sql = "SELECT * FROM `transaction_log` WHERE USER_ID = '$USER_ID' $filter ORDER BY $order, LOG_ID DESC LIMIT $limit OFFSET $offset";
    $result = mysqli_query($con,$sql);
    $resultnum = mysqli_num_rows($result);

    if ($limit !== 3 && $resultnum !== 0) echo "<h2 class='noResult'>Result (".$resultnum.")</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($count === 1) $a = "Flist";
        elseif ($count === 2) $a = "Slist";
        else $a = "";
        if ($count % 2 === 1) $b = 'odd';
        else $b = 'even';
        if ($count === $resultnum && $resultnum % 2 === 1) $c = "style='width:100%;'";
        else $c = '';

        $date = $row['date'];
        $amount = "Rp".abs($row['amount']);
        $amount = $row['amount'] < 0 ? "- ".$amount : ($row['amount'] > 0 ? "+ ".$amount : "FREE");
        $note = htmlspecialchars($row['note']);
        $type = $row['amount'] <=> 0;
        echo <<<HTML
        <div class='list $a $b' $c>
            <h4 class='t$type'>$amount</h4>
            <p>Transaction date: $date</p>
            <p>Note: $note</p>
        </div>
HTML;
        $count++;
    }    
    if ($resultnum === 0) echo "<h3 class='noResult'>- NO RESULT -<br><label for='all'>reset filter</label></h3>";
}

function get_wishlist($limit, $offset, $order, $filter, $goal) {
    global $con, $USER_ID, $USER_BALANCE;
    $count = 1;
    $sql = $goal ? "SELECT * FROM `wishlist` WHERE USER_ID = '$USER_ID' AND goal = '1'"
    : "SELECT * FROM `wishlist` WHERE USER_ID = '$USER_ID' AND goal = '0' $filter ORDER BY $order, WISHLIST_ID DESC LIMIT $limit OFFSET $offset";
    $result = mysqli_query($con,$sql);
    $resultnum = mysqli_num_rows($result);

    if ($goal) echo"<h2>GOAL</h2>";
    if (!$goal && $limit !== 3 && $resultnum !== 0) echo "<h2 class='noResult'>Result ($resultnum)</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($count === 1) $a = "Flist";
        elseif ($count === 2) $a = "Slist";
        else $a = "";
        if ($count % 2 === 1) $b = 'odd';
        else $b = 'even';
        if ($count === $resultnum && $resultnum % 2 === 1) $c = "style='width:100%;'";
        else $c = '';

        $WISHLIST_ID = $row['WISHLIST_ID'];
        $name = htmlspecialchars($row['name']);
        $price = $row['price'];
        $formated_price = $price > 0 ? "Rp".number_format($price,0,'.','.') : 'FREE';
        $priority = $row['priority'];
        $h = $priority == 'high' ? 'selected' : '';
        $m = $priority == 'medium' ? 'selected' : '';
        $l = $priority == 'low' ? 'selected' : '';
        $target = $row['target'] === '9999-12-31' ? '-' : $row['target'];
        $progress = ($price == 0) ? 100 : round($USER_BALANCE / $price * 100 , 2);
        $fulfilled = $row['fulfilled'];
        $date_fulfilled = $row['date_fulfilled'];
        $isfulfilled = '';

        if ($progress >= 100 && $fulfilled != '1') {
            $progress = "<span class='full'>100%</span>";
            $isfulfilled = "<input type='submit' value='Fulfil' form='ff$WISHLIST_ID'>";
        } elseif ($fulfilled == '1') $progress = "<span class='t1'>Fulfilled</span>";
        else $progress .= "%";
        

        if ($goal) $isgoal = "<input type='submit' value='Unmark as GOAL' form='umg$WISHLIST_ID'>";
        else $isgoal = "<input type='submit' value='Mark as GOAL' form='mg$WISHLIST_ID'>";    

        echo <<<HTML
        <div class='list $a $b' $c>
            <form action="" method='post' id='$WISHLIST_ID' inert>
                <input type="number" value='$WISHLIST_ID' name='editWL[id]' hidden>
                <table> 
                    <tr>
                        <td><b>Name:</b></td>
                        <td><input type='text' value='$name' name='editWL[name]'></td>
                    </tr>
                    <tr>
                        <td><b>Price:</b></td>
                        <td><input type="text" inputmode="numeric" id='price$WISHLIST_ID' value='$formated_price' placeholder='$price' name='editWL[price]'></td>
                    </tr>
                    <tr>
                        <td><b>Priority:</b></td>
                        <td>
                            <select name="editWL[priority]">
                                <option>unset</option>
                                <option value="high" $h>High</option>
                                <option value="medium" $m>Medium</option>
                                <option value="low" $l>Low</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Get Before:</b></td>
                        <td><input type='text' value='$target' name='editWL[target]' id='target$WISHLIST_ID'></td>
                    </tr>
                    <tr>
                        <td><b>Progress:</b></td>
                        <td>$progress</td>
                    </tr>
                </table>
            </form>

            <form action="" method='post'  id='umg$WISHLIST_ID' hidden>
                <input type="number" value='$WISHLIST_ID' name='unmark'>
            </form>
            <form action="" method='post'  id='mg$WISHLIST_ID' hidden>
                <input type="number" value='$WISHLIST_ID' name='mark'>
            </form>
            <form action="" method='post' id='del$WISHLIST_ID' hidden>
		        <input type="number" value='$WISHLIST_ID' name='deleteWL'>
	        </form>
            <form action="" method='post' id='ff$WISHLIST_ID' hidden>
		        <input type="number" value='$WISHLIST_ID' name='fulfilWL[id]'>
		        <input type="text" value='$name' name='fulfilWL[name]'>
		        <input type="number" value='$price' name='fulfilWL[price]'>
            </form>

            <div class='action'>
                <div>
                    <input type="submit" value='Save' form='$WISHLIST_ID'>
                    $isgoal
                    <input type="submit" value='Delete' form='del$WISHLIST_ID'>
                </div>
                <button onclick="editwl(this,'$WISHLIST_ID')">Edit</button>
                $isfulfilled 
            </div>
        </div>   
HTML;
        $count++;
    }
    if ($goal && $resultnum === 0) echo "<h3 class='noResult'>- NO GOAL SET -<br></h3>";
    elseif ($resultnum === 0) echo "<h3 class='noResult'>- NO RESULT -<br><label for='all'>reset filter</label></h3>";
}

if (isset($_POST['editName'])) {
    $editName = $_POST['editName'];
    $sql = $con->prepare("UPDATE `user` SET name = ? WHERE USER_ID = ?");
    $sql->bind_param('si', $editName, $USER_ID);
    $sql->execute();
    $USER_NAME = htmlspecialchars($editName);
    $msg = '<h3>Notification!</h3>Name Has Been Edited Successfuly!';
}

if (isset($_FILES["image"])) {
    $style = [
        $_POST['style']['x'],
        $_POST['style']['y'],
        $_POST['style']['z'],
        $_POST['style']['css']
    ];
    if ($_FILES['image']['size'] != 0) {
        $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image']['tmp_name']);
        $errnum = $errmsg = NULL;
        if (!preg_match('/^image\/(png|jpe?g|webp|gif)$/i', $finfo)) {
            $errmsg .= 'Unsupported or Invalid Image File';
            $errnum += 1;
        }
        if ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            if ($errmsg !== '') $errmsg .= "<br>";
            $errmsg .= 'Image Size is Too Large (max 3MB)';
            $errnum += 1;
        } 
        $errnum = $errnum > 1 ? $errnum = "($errnum)" : "";
        if ($errmsg === NULL) {
            foreach (glob("uploads/$USER_ID.*") as $oldP) unlink($oldP);  
            $target = "uploads/$USER_ID." . substr($finfo, 6);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
                file_put_contents("uploads/style-$USER_ID.json", json_encode($style));
                $msg = '<h3>Notification!</h3>Image Updated Successfully!';
                $_SESSION['ts'] = "?ts=".time();
            } else $msg = 'Unexpected Error';
        } else $msg = "<h3>WARNING! $errnum</h3>$errmsg";
    } else {
        file_put_contents("uploads/style-$USER_ID.json", json_encode($style));
        $msg = '<h3>Notification!</h3>Image Edited Successfully!';
    }
} 

function get_stats() {
    global $con, $USER_ID;
    
    $transactionQ = mysqli_query($con, "SELECT
        COUNT(*) AS total_transaction,
        COUNT(CASE WHEN amount > 0 THEN 1 END) AS income_count,
        SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) AS total_income,
        COUNT(CASE WHEN amount < 0 THEN 1 END) AS expense_count,
        SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END) AS total_expense
        FROM transaction_log WHERE USER_ID = $USER_ID");
    $transaction = mysqli_fetch_assoc($transactionQ);

    $wishlistQ = mysqli_query($con, "SELECT
        COUNT(*) AS total_wish,
        COUNT(CASE WHEN priority = 'low' THEN 1 END) AS low_count,
        COUNT(CASE WHEN priority = 'medium' THEN 1 END) AS medium_count,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS high_count,
        SUM(fulfilled = 1) AS fulfilled_count
        FROM wishlist WHERE USER_ID = $USER_ID");
    $wishlist = mysqli_fetch_assoc($wishlistQ);

    return "<br>
    <b>Total Transaction:</b> <span>".$transaction['total_transaction']."</span>
    <ul>
        <li>Income: <span>Rp".number_format($transaction['total_income'],'0','','.')." (".$transaction['income_count'].")</span></li>
        <li>Expense: <span>Rp".number_format(-1 * $transaction['total_expense'],'0','','.')." (".$transaction['expense_count'].")</span></li>
    </ul>
    <br>
    <b>Total Whislist:</b> <span>".$wishlist['total_wish']."</span>
    <ul>
        <li>Low Priority: <span>".$wishlist['low_count']."</span></li>
        <li>Medium Priority: <span>".$wishlist['medium_count']."</span></li>
        <li>High Priority: <span>".$wishlist['high_count']."</span></li>
        <li>Fulfiled: <span>".$wishlist['fulfilled_count']."</span></li>
    </ul>";
}

if (isset($msg)) echo "<span class='notif' id='dn'>$msg</span>";

$_SESSION['hidebal'] = $_SESSION['hidebal'] ?? false;
if (isset($_POST['hidebal'])) $_SESSION['hidebal'] = $_POST['hidebal'] == 'true' ? true : false; 
$baltype = $_SESSION['hidebal'] ? 'password' : 'text';
$balchecked = $_SESSION['hidebal'] ? 'checked' : '';
$eye = $_SESSION['hidebal'] ? "fa-eye-slash" : "fa-eye";
?>