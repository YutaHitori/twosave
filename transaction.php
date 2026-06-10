<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Transaction - twosave</title>
</head>
<body>
<?php 
if (!isset($_COOKIE['user'])) {
    header("Location: about.php");
    exit;
}

include 'GETUSERINFO.php';
include 'style.php';

$sort_option = $_SESSION['transaction_sort_option'] = $_GET['sort'] ?? $_SESSION['transaction_sort_option'] ?? 'added_date_desc';
$filter_option = $_SESSION['transaction_filter_option'] = $_GET['filter'] ?? $_SESSION['transaction_filter_option'] ?? ['all'];
switch ($sort_option) {
    case 'added_date_desc' :
        $order = "LOG_ID DESC"; break;    
    case 'added_date_asc' :
        $order = "LOG_ID ASC"; break;
    case 'transaction_date_desc' :
        $order = "date DESC"; break;
    case 'transaction_date_asc' :
        $order = "date ASC"; break;
    default : $order = "LOG_ID DESC";
} 
if (!in_array('all', $filter_option)) {
    $filter = "AND (";
    $filter .= in_array('expense', $filter_option) ? "amount <= '0' OR " : '';
    $filter .= in_array('income', $filter_option) ? "amount > '0' OR " : '';
    $filter .= "'')";
} else $filter = '';
?>
<div class='grid transactionpage'>
    <h1 id='title' class='window'>Transaction</h1>
    
    <div id='sortfilter' class='window'>
        <form method="GET" onchange="sortFilter(this)">
            Sort by :
            <select name="sort" id="sort">
                <option value="added_date_desc" <?= $sort_option === 'added_date_desc' ? 'selected' : '' ?>>Date added (Newest to Oldest)</option>
                <option value="added_date_asc" <?= $sort_option === 'added_date_asc' ? 'selected' : '' ?>>Date added (Oldest to Newest)</option>
                <option value="transaction_date_desc" <?= $sort_option === 'transaction_date_desc' ? 'selected' : '' ?>>Transaction Date (Latest to Oldest)</option>
                <option value="transaction_date_asc" <?= $sort_option === 'transaction_date_asc' ? 'selected' : '' ?>>Transaction Date (Oldest to Latest)</option>
            </select>   
            <input type="checkbox" id='all' value='all' name='filter[]' <?= in_array('all', $filter_option) ? 'checked' : '' ?> onclick='filterAll()'>
            <label for="all">ALL</label>
            <input type="checkbox" id='expense' value='expense' name='filter[]' <?= in_array('expense', $filter_option) ? 'checked' : '' ?>>
            <label for="expense">Expense</label>
            <input type="checkbox" id='income' value='income' name='filter[]'<?= in_array('income', $filter_option) ? 'checked' : '' ?>>
            <label for="income">Income</label>
        </form> 
    </div>

    <div id='transaction' class='window'>
        <?php get_transaction(99, 0, $order, $filter); ?>
    </div>

    <div id='copyright'>&copy; 2025 Hitori Yuta. All rights reserved.</div>

    <form action="" method="post" id='addTRForm' class='window' style='display: none;'>
        <h3>Add Transaction</h3>
        <table> 
            <tr>
                <td><br><b>Transaction date:</b></td>
                <td><input type="date" value="<?=$TODAY?>" name='addTR[date]'></td>
            </tr>
            <tr>
                <td><br><b>Amount (IDR):</b></td>
                <td><input type="number" min='0' placeholder='0' name='addTR[amount]'></td>
            </tr>
            <tr>
                <td><br><b>Note:</b></td>
                <td><input type="text" placeholder='food' name='addTR[note]'></td>
            </tr>
        </table>
        <br>
        <div class='spanCheck'>
            <input type="radio" name='addTR[type]' id='earn' value='1' checked hidden><label for="earn">income</label>
            <input type="radio" name='addTR[type]' id='spend' value='2' hidden><label for="spend">expense</label>
        </div>
        <div class='submit'><input type="submit"></div>
    </form>
    
    <div id='nav'>
        <nav onclick="window.location.href = 'overview.php';"><i class="fa-duotone fa-solid fa-house-chimney fa-xl"></i><span>Overview</span></nav>
        <nav onclick="window.location.href = 'transaction.php';"><i class="fa-duotone fa-solid fa-money-check-dollar-pen fa-xl active"></i><span>transaction</span></nav>
        <input type='checkbox' id='TRbutton' hidden>
        <label for='TRbutton' onclick='showAddTransaction()'><i class="fa-solid fa-plus fa-xl" id='TRlabel'></i></label>
        <nav onclick="window.location.href = 'wishlist.php';"><i class="fa-duotone fa-solid fa-clipboard-list fa-xl"></i><span>Wishlist</span></nav>
        <nav onclick="window.location.href = 'profile.php';"><i class="fa-duotone fa-solid fa-id-card fa-xl"></i><span>Profile</span></nav>
    </div>
</div>
</body>
</html>