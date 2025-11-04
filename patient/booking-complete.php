<?php

//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
  if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
  } else {
    $useremail = $_SESSION["user"];
  }
} else {
  header("location: ../login.php");
}


//import database
include("../connection.php");

// PERBAIKAN: Konversi kueri pengambilan user dari MySQLi ke PDO
$sqlmain = "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->execute([$useremail]);
$userfetch = $stmt->fetch(PDO::FETCH_ASSOC);
$userid = $userfetch["pid"];
$username = $userfetch["pname"];


if ($_POST) {
  if (isset($_POST["booknow"])) {
    $apponum = $_POST["apponum"];
    $scheduleid = $_POST["scheduleid"];
    $date = $_POST["date"];
    
    // PERBAIKAN KRITIS: Menggunakan Prepared Statement untuk INSERT yang aman (mengganti string concatenation)
    $sql2 = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) VALUES (?, ?, ?, ?)";
    $stmt_insert = $database->prepare($sql2);
    
    // Eksekusi Prepared Statement
    $stmt_insert->execute([$userid, $apponum, $scheduleid, $date]);

    header("location: appointment.php?action=booking-added&id=" . $apponum . "&titleget=none");
  }
}
?>