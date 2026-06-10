<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Log in - twosave</title>
</head>
<body>
<?php
if (isset($_COOKIE['user'])) {
    header("Location: index.php");
    exit;
}

$con = mysqli_connect('localhost','root','', 'projek');
$email = '';
$PEM = $EEM = '';
if (isset($_POST['email'])) {
    $email = strtolower($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $_POST['email'] !== "ADMIN@NIMDA") $EEM = '*invalid email format!';
    else {
        $sql = $con->prepare("SELECT * FROM user WHERE email = ?");
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if (mysqli_num_rows($result) == 0) $EEM = ' *email have not been registered';
        else {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($_POST['password'], $row['password'])) {
                $auth_token = bin2hex(random_bytes(32));
                $sql = "UPDATE `user` SET auth_token='$auth_token' WHERE email = '$email'";
                mysqli_query($con, $sql);
                setcookie('user', $auth_token, time() + (86400 * 30), "/"); 
                echo <<<HTML
                <script>setTimeout(function () {window.location.href = 'index.php';}, 3000)</script>
                <div class='window'  id='inForm'>
                    <h1>Login Successful</h1>
                    <br>
                    <p>You will be redirected shortly.<br><br>If the redirection doesn't happen, <a 'href="index.php">click here</a> to go to the main menu.</p>
                </div>
HTML;
                $isLogged = true;
            } else $PEM = '*wrong password';
        }
    }  
    if (empty($_POST['email'])) $EEM = '*required';
    if (empty($_POST['password'])) $PEM = '*required'; 
}

if (!isset($isLogged)) {
    echo <<<HTML
    <form action="" method='post' class='window' id='inForm'>
        <h1>Login</h1>
        <br>
        <table>
            <tr>
                <td>Email <span id='EEM' class='errMsg'>$EEM</span></td>
                <td><input type="email" name='email' value='$email' id='email'></td>
            </tr>
            <tr>
                    <td><br>Password <span id='PEM' class='errMsg'>$PEM</span></td>
                <td><input type="password" name='password' id='password'></td>
            </tr>
        </table>
        <br>
        <input type="checkbox" id='showPass'>
        <label for="showPass" class='spanCheck' onclick='showPass(false)'>Show Password<span id='checkIcon'></span></label>
        <div class='submit'><input type="submit" value='Login' onclick='validateForm(event,false)'></div>
    </form>
    <section>Don't Have an Account? <a ' href="register.php">Register</a><br><br>&copy; 2025 Hitori Yuta. All rights reserved.</section>
HTML;
}
include 'style.php';
?>
</body>
</html>