<?php include 'GETUSERINFO.php';
if(isset($_POST["submit"])) {
    $title = $_POST['title'];
    $details = $_POST['details'];
    $log = date("Y-m-d h:i:sa")."\nreport created";
    $sql = "INSERT INTO report (USER_ID, title, details, log)
    VALUES ('$USER_ID', '$title', '$details', '$log')";
    mysqli_query($con,$sql);
    echo mysqli_insert_id($con);
    if (isset($_FILES)) {
        $target_file = "reports/". mysqli_insert_id($con) . "." . basename($_FILES["image"]["type"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file has been uploaded.";
        } else echo "There was an error uploading your file.";
    }
}
echo password_hash("qwertyuiopP1", PASSWORD_DEFAULT);
?>
<form action="" method='post' enctype="multipart/form-data">
    <h1>Report</h1>
    title: <input type="text" name='title' placeholder='Insert Report Title Here'><br>
    details: <br>
    <textarea name="details" rows="10" cols="30" placeholder='Describe Your Problem Here'></textarea>
    <br>
    attachment (optional): <input type="file" name='image'>
    <br>
    <input type="submit" name='submit'>
</form>