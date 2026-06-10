<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Wishlist - twosave</title>
</head>
<body>
<?php 
if (!isset($_COOKIE['user'])) {
    header("Location: about.php");
    exit;
}

include 'GETUSERINFO.php';
include 'style.php';

$sort_option = $_SESSION['wislist_sort_option'] = $_GET['sort'] ?? $_SESSION['wislist_sort_option'] ?? 'date_desc';
$filter_option = $_SESSION['wislist_filter_option'] = $_GET['filter'] ?? $_SESSION['wislist_filter_option'] ?? ["all"];
switch ($sort_option) {
    case 'date_desc' :
        $order = "WISHLIST_ID DESC"; break;
    case 'date_asc' :
        $order = "WISHLIST_ID ASC"; break;
    case 'priority_desc' :
        $order = "FIELD(priority, 'high', 'medium', 'low')"; break;
    case 'priority_asc' :
        $order = "FIELD(priority, 'low', 'medium', 'high')"; break;
    case 'price_desc' :
        $order = "price DESC"; break;
    case 'price_asc' :
        $order = "price ASC"; break;
    case 'target_desc' :
        $order = "FIELD(target, '9999-12-31'), target DESC"; break;
    case 'target_asc' :
        $order = "FIELD(target, '9999-12-31'), target ASC"; break;
    default : $order = "WISHLIST_ID DESC";
}
if (!in_array('all', $filter_option)) {
    $filter = "AND (";
    $filter .= in_array('progress_complete', $filter_option) ? "price <= '$USER_BALANCE' OR " : '';
    $filter .= in_array('progress_incomplete', $filter_option) ? "price > '$USER_BALANCE' OR " : '';
    $filter .= in_array('fulfilled', $filter_option) ? "fulfilled = '1' OR " : '';
    $filter .= in_array('ongoing', $filter_option) ? "fulfilled = '0' OR " : '';
    $filter .= "'')";
} else $filter = '';
?>
<div class='grid wishlistpage'>
    <h1 id='title' class='window'>Wishlist</h1>

    <div id='goal' class='window'>
        <?= get_wishlist(1, 0, 'WISHLIST_ID desc', '', true) ?>
    </div>

    <div class='window' id='sortfilter'>
        <form method="GET" onchange="sortFilter(this)">
            Sort by :
            <select name="sort" id="sort" style='outline: none; border: none;'>
                <option value="date_desc" <?= $sort_option === 'date_desc' ? 'selected' : '' ?>>Date added (Newest to Oldest)</option>
                <option value="date_asc" <?= $sort_option === 'date_asc' ? 'selected' : '' ?>>Date added (Oldest to Newest)</option>
                <option value="priority_desc" <?= $sort_option === 'priority_desc' ? 'selected' : '' ?>>Priority (High to Low)</option>
                <option value="priority_asc" <?= $sort_option === 'priority_asc' ? 'selected' : '' ?>>Priority (Low to High)</option>
                <option value="price_desc" <?= $sort_option === 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                <option value="price_asc" <?= $sort_option === 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                <option value="target_desc" <?= $sort_option === 'target_desc' ? 'selected' : '' ?>>target (Farthest to Closest)</option>
                <option value="target_asc" <?= $sort_option === 'target_asc' ? 'selected' : '' ?>>target (Closest to Farthest)</option>
            </select>
            <input type="checkbox" id='all' value='all' name='filter[]' <?= in_array('all', $filter_option) ? 'checked' : '' ?> onclick='filterAll()'>
            <label for="all">ALL</label>
            <input type="checkbox" id='progress_complete' value='progress_complete' name='filter[]' <?= in_array('progress_complete', $filter_option) ? 'checked' : '' ?>>
            <label for="progress_complete">Progress Complete</label>
            <input type="checkbox" id='progress_incomplete' value='progress_incomplete' name='filter[]' <?= in_array('progress_incomplete', $filter_option) ? 'checked' : '' ?>>
            <label for="progress_incomplete">Progress Inclomplete</label>
            <input type="checkbox" id='fulfilled' value='fulfilled' name='filter[]' <?= in_array('fulfilled', $filter_option) ? 'checked' : '' ?>>
            <label for="fulfilled">Fulfilled</label>
            <input type="checkbox" id='ongoing' value='ongoing' name='filter[]'<?= in_array('ongoing', $filter_option) ? 'checked' : '' ?>>
            <label for="ongoing">Ongoing</label>
        </form>
    </div>
    
    <div id='wishlist' class='window'>
        <?= get_wishlist(99, 0, $order, $filter, false) ?>
    </div>

    <div id='copyright'>&copy; 2025 Hitori Yuta. All rights reserved.</div>

    <form action="" method="post" id='addWLForm' class='window' style='display: none;'>
        <h3>Add to Wishlist</h3>
        <table> 
            <tr>
                <td><b>Name:</b></td>
                <td><input type="text" placeholder="brand new phone" name='addWL[name]' id='formName'></td>
            </tr>
            <tr>
                <td><b>Price:</b></td>
                <td><input type="number" min='0' placeholder='0' name='addWL[price]'></td>
            </tr>
            <tr>
                <td><b>Priority:</b></td>
                <td>
                    <select id="priority" name="addWL[priority]">
                        <option value='unset'> --select-- </option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Get Before:</b></td>
                <td><input type="date" name='addWL[target]'></td>
            </tr>
        </table>
        
        <input type="checkbox" name='addWL[goal]' id='mag' onclick='checkIcon()'>
        <label for='mag' class='spanCheck' >Mark as GOAL <span id='checkIcon'></span></label>
        <div class='submit'><input type="submit"></div>
    </form>

    <div id='nav'>
        <nav onclick="window.location.href = 'overview.php';"><i class="fa-duotone fa-solid fa-house-chimney fa-xl"></i><span>Overview</span></nav>
        <nav onclick="window.location.href = 'transaction.php';"><i class="fa-duotone fa-solid fa-money-check-dollar-pen fa-xl"></i><span>Transaction</span></nav>
        <input type='checkbox' id='WLbutton' hidden>
        <label for='WLbutton' onclick='showAddWish()'><i class="fa-solid fa-plus fa-xl" id='WLlabel'></i></label>
        <nav onclick="window.location.href = 'wishlist.php';"><i class="fa-duotone fa-solid fa-clipboard-list fa-xl active"></i><span>Wishlist</span></nav>
        <nav onclick="window.location.href = 'profile.php';"><i class="fa-duotone fa-solid fa-id-card fa-xl"></i><span>Profile</span></nav>
</div>
</body>
</html>