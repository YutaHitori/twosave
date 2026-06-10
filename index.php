
<?php
if (isset($_COOKIE['user'])) header("Location: overview.php");
else header("Location: about.php");
?>
