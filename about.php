<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - twosave</title>
</head>
<body>
<?php
include "style.php";
$a = (!isset($_COOKIE['user']))
? "<button onclick=\"window.location.href = 'login.php';\"><span>Log in</span></button> <button onclick=\"window.location.href = 'register.php';\"><span>Register</span></button>"
: "<button onclick=\"window.location.href = 'overview.php';\"><span>Main Menu</span></button>";
?>
<div class='aboutpage'>
    <h1>
        Wellcome to twosave <br>
        <?= $a ?>
    </h1>
    
    <div id='about'>
        <h2>About twosave</h2><br><br>
        <p>twosave is a personal finance management web application designed to help you track your income, expenses, and wishlist items.</p>
        <p>Whether you're saving for a dream gadget or just keeping track of your daily expenses, twosave makes it simple through it's friendly interface.</p><br>
        <p>With twosave, you can can quickly add transactions, keep an eye on your balance, and create wishlists for things you want to save up for.</p>
        <p>This project was created and developed by Hitori Yuta in 2025 as a practical tool for everyday budgeting and financial planning</p><br>
        <p>Thank you for using twosave!</p>
    </div>

    <br><div id="copyright">&copy; 2025 Hitori Yuta. All rights reserved.</div><br>
</div>
</body>
</html>