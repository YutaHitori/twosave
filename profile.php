<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Profile - twosave</title>
</head>
<body>
<span id='notif'></span>
<?php 
if (!isset($_COOKIE['user'])) {
    header("Location: about.php");
    exit;
}

include 'GETUSERINFO.php';
include 'style.php';

if (file_exists("uploads/style-$USER_ID.json")) {
    $style = json_decode(file_get_contents("uploads/style-$USER_ID.json"), true);
    list($x, $y, $z, $css) = $style;
} else {
    $x = '50';
    $y = '50';
    $z = '1';
    $css = 'width:100%;';
}
$path = glob("uploads/$USER_ID.*")[0] ?? "uploads/default-avatar.jpg";
$path .= $_SESSION['ts'] ?? '';
$stats = get_stats();

echo <<<HTML
<div onclick='uploadPP()' id='cce'></div>
<div class='grid profilepage'>
    <h1 id='title' class='window'>Profile</h1>

    <div id='photo' class='window' onclick='uploadPP()'>
        <input type="checkbox" id='uploadPP'>
        <h3>Edit</h3>
        <img src="$path" style="$css">
    </div>
    
    <div id='name' class='window'>
        <form method='post' id='editName'>
            <input type='text' name='editName' value='$USER_NAME' id='editNameVal' inert hidden>
            <div>$USER_NAME<i class="fa-duotone fa-solid fa-pencil" onclick='changeName()'></i></div> 
            <button type="button" onclick='changeName()'>Cancel</button>
            <button type='submit'>Save</button>
        </form>
    </div> 
    
    <div id='info' class='window'>
        <h2>Personal Information</h2>
        <br><b>ID :</b> <span>#$HEX_USER_ID</span>
        <br><b>Role :</b> <span>$USER_ROLE</span>
        <br>
        <label for="hideBalance" id='hidballab'>
            <b>My Balance :</b>
            <span>Rp<input type='$baltype' style='border: none;' value='$USER_BALANCE_FORMATED' id='bal' inert> <i class="fa-duotone fa-solid $eye" id='hideEye'></i></span>
        </label>
        <input type="checkbox" id='hideBalance' onchange="hideBalance(this.checked)" $balchecked>
        <br><b>Email :</b> <span>$USER_EMAIL</span>
    </div>
    
    <div id='stats' class='window'>
        <h2>Stats</h2><br>
        <b>Account Created:</b> <span>$DATE_CREATED</span><br>
        $stats
    </div>

    <form action="" method='post' id='logout' class='window'>
        <span id='logoutC' hidden>
            <b>Are You Sure?</b> 
            <input type="submit" value='Yes' name='logout'>
        </span>
        <input type="checkbox">
        <label onclick="logout(this);">Logout</label>
    </form>

    <div id='copyright'>&copy; 2025 Hitori Yuta. All rights reserved.</div>

    <div id='nav'>
        <nav onclick="window.location.href = 'overview.php';"><i class="fa-duotone fa-solid fa-house-chimney fa-xl"></i><span>overview</span></nav>
        <nav onclick="window.location.href = 'transaction.php';"><i class="fa-duotone fa-solid fa-money-check-dollar-pen fa-xl"></i><span>transaction</span></nav>
        <label><i class="fa-duotone fa-solid fa-minus fa-xl" id='disabled'></i></label>
        <nav onclick="window.location.href = 'wishlist.php';"><i class="fa-duotone fa-solid fa-clipboard-list fa-xl"></i><span>wishlist</span></nav>
        <nav onclick="window.location.href = 'profile.php';"><i class="fa-duotone fa-solid fa-id-card fa-xl active"></i><span>profile</span></nav>
    </div>
</div>

<form action="" method='post' enctype="multipart/form-data" id='uploadForm' class='window' oninput='update()' hidden>
    <label for='upload'>
        <div>
           <h2>Change Photo</h2>
           <p>.png .jpeg .jpg .webp .gif<br>(max 3MB)</p>
        </div>
        <img src='$path' alt='preview' id='preview' style="$css">
    </label>
    <input type="range" name='style[y]' min="0" max="100" step='0.1' value="$y" id='y'>
    <input type="range" name='style[x]' min="0" max="100" step='0.1' value="$x" id='x'>
    zoom (up to 5x):
    <input type="range" name='style[z]' min="1" max="5" step='0.02' value="$z" id='zoom'>
    <input type="text" name='style[css]' id='style' value='$css' hidden>
    <input type="file" name='image' id='upload' onchange='previewImage(event,this)' hidden>
    <span>
        <button type="button" onclick='uploadPP()'>Close</button>
        <button type="button" onclick='revert()' id=''>Revert Changes</button>
        <input type="submit" name='submit' value='save'>
    </span>
</form>
HTML;
?>
</body>
</html>