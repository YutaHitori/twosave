<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Overview - twosave</title>
</head>
<body>
<?php 
if (!isset($_COOKIE['user'])) {
    header("Location: about.php");
    exit;
}

include "GETUSERINFO.php";
include 'style.php';
echo <<<HTML
<div class='grid overviewpage'>
    <h1 id='title' class='window'><p>Wellcome, $USER_NAME!</p></h1>
    
    <div id='balance' class='window'>
        <label for="hideBalance" id='hidballab'>
            <h3>My Balance :</h3>
            <span>Rp<input type='$baltype' style='border: none;' value='$USER_BALANCE_FORMATED' id='bal' inert> <i class="fa-duotone fa-solid $eye" id='hideEye'></i></span>
        </label>
        <input type="checkbox" id='hideBalance' onchange="hideBalance(this.checked)" $balchecked>
    </div>

    <div id='quick_action' class='window'>
        <h3>Quick Action</h3>
        <div>
            <input type='checkbox' id='TRbutton'>
            <label for='TRbutton' onclick='showAddTransaction(true)' id='TRlabel'>Add Transaction</label>
            <input type='checkbox' id='WLbutton'>
            <label for='WLbutton' onclick='showAddWish(true)' id='WLlabel'>Add Wish</label>
        </div>
    </div>

    <div id='goal' class='window'>
HTML;
        get_wishlist(1, 0, 'WISHLIST_ID desc', '', true);
echo <<<HTML
    </div>
    
    <div id='transaction' class='window'>
        <a href='transaction.php'>See All</a>
        <h3>Last Transaction</h3>
HTML;
        get_transaction(3, 0, 'LOG_ID desc', '');
echo <<<HTML
    </div>

    <div id='wishlist' class='window'>
        <a href='wishlist.php'>See All</a>
        <h3>Last Added Wish</h3>
HTML;
        get_wishlist(3, 0, 'WISHLIST_ID desc', '', false);
echo <<<HTML
    </div>

    <div id='copyright'>&copy; 2025 Hitori Yuta. All rights reserved.</div>

    <form action="" method="post" id='addTRForm' class='window' style='display: none;'>
        <h3>Add Transaction</h3>
        <table> 
            <tr>
                <td><br><b>Transaction Date:</b></td>
                <td><input type="date" value="$TODAY" name='addTR[date]'></td>
            </tr>
            <tr>
                <td><br><b>Amount (IDR):</b></td>
                <td><input type="number" min='0' value='0' name='addTR[amount]'></td>
            </tr>
            <tr>
                <td><br><b>Note:</b></td>
                <td><input type="text" placeholder='Food' name='addTR[note]'></td>
            </tr>
        </table>
        <br>
        <div class='spanCheck'>
            <input type="radio" name='addTR[type]' id='earn' value='1' checked hidden><label for="earn">income</label>
            <input type="radio" name='addTR[type]' id='spend' value='2' hidden><label for="spend">expense</label>
        </div>
        <div class='submit'><input type="submit"></div>
    </form>

    <form action="" method="post" id='addWLForm' class='window' style='display: none;'>
        <h3>Add to Wishlist</h3>
        <table> 
            <tr>
                <td><br><b>Name:</b></td>
                <td><input type="text" placeholder="Brand new phone" name='addWL[name]' id='formName'></td>
            </tr>
            <tr>
                <td><br><b>Price:</b></td>
                <td><input type="number" min='0' placeholder='0' name='addWL[price]'></td>
            </tr>
            <tr>
                <td><br><b>Priority:</b></td>
                <td>
                    <select id="priority" name="addWL[priority]">
                        <option> --select-- </option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><br><b>Get Before:</b></td>
                <td><input type="date" name='addWL[target]'></td>
            </tr>
        </table>
        <br>
        <input type="checkbox" name='addWL[goal]' id='mag' onclick='checkIcon()'>
        <label for='mag' class='spanCheck'>Mark as GOAL <span id='checkIcon'></span></label>
        <div class='submit'><input type="submit"></div>
    </form>
    
    <div id='nav'>
        <nav onclick="window.location.href = 'overview.php';"><i class="fa-duotone fa-solid fa-house-chimney fa-xl active"></i><span>Overview</span></nav>
        <nav onclick="window.location.href = 'transaction.php';"><i class="fa-duotone fa-solid fa-money-check-dollar-pen fa-xl"></i><span>Transaction</span></nav>
        <label><i class="fa-duotone fa-solid fa-minus fa-xl" id='disabled'></i></label>
        <nav onclick="window.location.href = 'wishlist.php';"><i class="fa-duotone fa-solid fa-clipboard-list fa-xl"></i><span>Wishlist</span></nav>
        <nav onclick="window.location.href = 'profile.php';"><i class="fa-duotone fa-solid fa-id-card fa-xl"></i><span>Profile</span></nav>
    </div>
</div>
HTML;
?>
</body>
</html>