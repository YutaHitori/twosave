<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Register - twosave</title>
</head>
<body>
<?php
if (isset($_COOKIE['user'])) {
    header("Location: index.php");
    exit;
}

$con = mysqli_connect('localhost','root','', 'projek');
$name = $NEM = $EEM = $PEM = $CPEM = '';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = strtolower($_POST['email']);
    if (empty($_POST['name'])) $NEM = '*Required';
    if ($_POST['password'] !== $_POST['passwordConfirm']) $PEM = $CPEM = " *password didn't match!";
    if (empty($_POST['password'])) $PEM = '*Required'; 
    if (empty($_POST['passwordConfirm'])) $PEM = '*Required'; 
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $EEM = '*invalid email format!';
    else {
        $sql = $con->prepare("SELECT * FROM user WHERE email = ?");
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if (mysqli_num_rows($result) > 0) $EEM = '*email already existed!';
        if (empty($NEM) && empty($EEM) && empty($PEM) && empty($CPEM)) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $auth_token = bin2hex(random_bytes(32));
            $sql1 = $con->prepare("INSERT INTO user (name, email, password, auth_token)
            VALUES (?, ?, ?, ?)");
            $sql1->bind_param('ssss', $name, $email, $password, $auth_token);
            $sql1->execute();
            setcookie('user', $auth_token , time() + (86400 * 30), "/"); 
            echo <<<HTML
                <script>setTimeout(function () {window.location.href = 'index.php';}, 3000)</script>
                <div class='window'  id='inForm'>
                    <h1>Registration Successful</h1><br>
                    <p>You will be redirected shortly.<br><br>If the redirection doesn't happen, <a ' href="index.php">click here</a> to go to the main menu.</p>
                </div>
HTML;
            $isLogged = true;
        }
    }
    if (empty($_POST['email'])) $EEM = '*Required';
}

if (!isset($isLogged)) {
    echo <<<HTML
    <form action="" method='post' class='window' id='inForm'>
        <h1>Registration</h1>
        <br>
        <table>
            <tr>
                <td>Username <span id='NEM' class='errMsg'>$NEM</span></td>
                <td><input type="text" name='name' value='$name' id='name'></td>
            </tr>
            <tr>
                <td><br>Email <span id='EEM' class='errMsg'>$EEM</span></td>
                <td><input type="email" name='email' id='email'></td>
            </tr>
            <tr>
            <td><br>Password <span id='PEM' class='errMsg'>$PEM</span></td>
            <td><input type="password" name='password' id='password' onkeyup='checkPass()'></td>
            <td id='passReqMsg' class='errMsg'></td>
            </tr>
            <tr>
                <td><br>Repeat Password <span id='CPEM' class='errMsg'>$CPEM</span></td>
                <td><input type="password" name='passwordConfirm' id='Cpassword'></td>
            </tr>
        </table>
        <br>
        <input type="checkbox" id='showPass'>
        <label for="showPass" class='spanCheck' onclick='showPass(true)'>Show Password<span id='checkIcon'></span></label>
        <div class='submit'><input type="submit" value='Register' onclick='validateForm(event,true)'></div>
    </form>
    <section>Already Have an Account? <a href="login.php">Log In</a><br><br>&copy; 2025 Hitori Yuta. All rights reserved.</section>
HTML; 
}
include 'style.php';
?>
</body>
</html>