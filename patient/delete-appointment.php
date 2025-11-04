<?php

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

include("../connection.php");

if ($_GET) {
    $id = $_GET["id"];
    $action = $_GET["action"];
    
    // Gunakan exec() PDO untuk DELETE
    $database->exec("DELETE FROM appointment WHERE appoid = $id;");
    
    header("location: appointment.php?action=droped");
}