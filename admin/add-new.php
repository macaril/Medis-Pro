<?php

//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}


//import database
include("../connection.php");


if ($_POST) {
    //print_r($_POST);
    
    // Ambil data dari POST
    $name = $_POST['name'];
    $nik = $_POST['nik'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password == $cpassword) {
        $error = '3';
        
        // PERBAIKAN KRITIS: Check if email exists in webuser (PDO Prepared Statement)
        $sql_check = "SELECT email FROM webuser WHERE email = ?";
        $stmt_check = $database->prepare($sql_check);
        $stmt_check->execute([$email]);
        
        // PDO: Mengambil hasil (jika ada)
        $user_exists = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($user_exists) {
            $error = '1'; // Email sudah terdaftar
        } else {

            // PERBAIKAN KRITIS: INSERT doctor (PDO Prepared Statement)
            $sql1 = "INSERT INTO doctor(docemail, docname, docpassword, docnic, doctel, specialties) VALUES(?, ?, ?, ?, ?, ?)";
            $stmt1 = $database->prepare($sql1);
            $stmt1->execute([$email, $name, $password, $nik, $tele, $spec]);

            // PERBAIKAN KRITIS: INSERT webuser (PDO Prepared Statement)
            $sql2 = "INSERT INTO webuser (email, usertype) VALUES(?, 'd')";
            $stmt2 = $database->prepare($sql2);
            $stmt2->execute([$email]);
            
            $error = '4'; // Sukses
        }

    } else {
        $error = '2'; // Konfirmasi password salah
    }

    // Redirect ke halaman doctors dengan status error
    header("location: doctors.php?action=add&error=" . $error);
}

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Doctor</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
    
   

</body>
</html>