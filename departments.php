<?php
// Calling Side Bar And Header Page And Deparments Code
require("includes/sideBar.php");
if ($cUser['role'] !== "Admin") {
    header("Location: 404.php");
    exit();
}
require("includes/header.php");
require("includes/departmentsCode.php");
require("includes/footer.php");
?>