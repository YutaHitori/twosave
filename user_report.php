<a href="index.php">main menu</a>
<a href="report.php">file a report</a>
<?php include 'GETUSERINFO.php';
if (isset($_POST['REPORT_ID'])) {
    $REPORT_ID = $_POST['REPORT_ID'];
    $log = "<br><br>".date("Y-m-d h:i:sa");
    if ($_POST['severity'] != $_POST['Lseverity']) $log .= "<br>severity set to -> ".$_POST['severity'];
    if ($_POST['status'] != $_POST['Lstatus']) $log .= "<br>status set to -> ".$_POST['status'];
    if (isset($_POST['comment'])) $log .= "<br>comment:<br>".$_POST['comment'];
    $sql = "UPDATE `report` SET severity='".$_POST['severity']."', status='".$_POST['status']."', log = CONCAT(log,'$log') WHERE REPORT_ID = $REPORT_ID";
    mysqli_query($con,$sql);
}
$sql = "SELECT * FROM `report`"; //WHERE status != 'pending'"; $filter ORDER BY $order, WISHLIST_ID DESC";
$result = mysqli_query($con,$sql);
while ($row = mysqli_fetch_assoc($result)) {
    $REPORT_ID = $row['REPORT_ID'];
    $authorid = $row['USER_ID'];
    $author = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM user WHERE USER_ID = $authorid"))['name'];
    $title = $row['title'];
    $severity = $row['severity'];
    $status = $row['status'];
    $datetime = $row['datetime'];
    $details = $row['details'];
    $log = $row['log'];
    echo <<<HTML
        <fieldset>
        <legend><h4>$title</h4></legend>
        <p>report ID: $REPORT_ID</p>
        <p>reported by: $author ($authorid)</p>
        <p>severity: $severity</p>
        <p>status: $status</p>
        <p>date reported: $datetime</p>
        <p>see details: $details</p>
        <p>report log: <br>$log</p>
HTML;
?>
        <?php if ($USER_ID == 1) { ?>
        <form action='' method='post'>
            <input type='number' value='<?= $REPORT_ID ?>' name='REPORT_ID' hidden>
            <input type='text' value='<?= $severity ?>' name='Lseverity' hidden>
            <label for='severity'>set severity:</label>
            <select name='severity' id='severity'>
                <option value='' <?= $severity === '' ? 'selected' : '' ?>>unset</option>
                <option value='low' <?= $severity === 'low' ? 'selected' : '' ?>>low</option>
                <option value='medium' <?= $severity === 'medium' ? 'selected' : '' ?>>medium</option>
                <option value='high' <?= $severity === 'high' ? 'selected' : '' ?>>High</option>
                <option value='critical' <?= $severity === 'critical' ? 'selected' : '' ?>>critical</option>
            </select>
            <input type='text' value='<?= $status ?>' name='Lstatus' hidden>
            <label for='status'>set status:</label>
            <select name='status' id='status'>
                <option value='pending' <?= $status === 'pending' ? 'selected' : '' ?>>pending</option>
                <option value='approved' <?= $status === 'approved' ? 'selected' : '' ?>>approved</option>
                <option value='wip' <?= $status === 'wip' ? 'selected' : '' ?>>wip</option>
                <option value='solved' <?= $status === 'solved' ? 'selected' : '' ?>>solved</option>
                <option value='resolved' <?= $status === 'resolved' ? 'selected' : '' ?>>resolved</option>
                <option value='graveyarded' <?= $status === 'graveyarded' ? 'selected' : '' ?>>graveyarded</option>
            </select>
            <label for='comment'>comment:</label>
            <input type='text' value='<?= $log ?>' name='log' hidden>
            <textarea name='comment' id='comment'></textarea>
            <input type='submit' value='set'>
            </form>
        <?php } ?> 
    </fieldset>
<?php } ?>
