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
    // Escape email parameter (PostgreSQL handles this safely through exec/prepare but quoting is sometimes needed for complex variables)
    $email = $database->quote($_GET["email"]);
    $id = $_GET["id"];

    // Menggunakan exec() PDO untuk DELETE (tidak mengembalikan hasil set)
    // Hapus appointment pasien
    $database->exec("DELETE FROM appointment WHERE pid = $id;");
    // Hapus data pasien
    $database->exec("DELETE FROM patient WHERE pid = $id;");
    // Hapus user dari webuser
    $database->exec("DELETE FROM webuser WHERE email = $email;");

    // Clear session and redirect to logout
    session_destroy();
    header("location: ../index.html");
}