<?php

//import database
include("../connection.php");


if ($_POST) {
    // Menghapus: $result= $database->query("select * from webuser");

    // Ambil data dari POST
    $name = $_POST['name'];
    $nik = $_POST['nik'];
    $oldemail = $_POST["oldemail"];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $id = $_POST['id00'];

    if ($password == $cpassword) {
        $error = '3';
        
        // PERBAIKAN KRITIS: Check if new email exists and belongs to a different doctor (PDO Prepared Statement)
        $sql_check = "SELECT doctor.docid FROM doctor INNER JOIN webuser ON doctor.docemail=webuser.email WHERE webuser.email = ?";
        $stmt_check = $database->prepare($sql_check);
        $stmt_check->execute([$email]);
        
        // PDO: Ambil ID dokter yang menggunakan email baru (jika ada)
        $result_fetch = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        // Tentukan ID dokter yang ditemukan, atau gunakan ID saat ini jika email unik
        $id2 = $result_fetch ? $result_fetch["docid"] : $id;

        // Periksa apakah email baru sudah digunakan oleh dokter lain ($id2 != $id)
        if ($id2 != $id) {
            $error = '1';
        } else {

            // PERBAIKAN KRITIS: UPDATE doctor (PDO Prepared Statement)
            $sql1 = "UPDATE doctor SET docemail=?, docname=?, docpassword=?, docnic=?, doctel=?, specialties=? WHERE docid=?";
            $stmt1 = $database->prepare($sql1);
            $stmt1->execute([$email, $name, $password, $nik, $tele, $spec, $id]);

            // PERBAIKAN KRITIS: UPDATE webuser (PDO Prepared Statement)
            $sql2 = "UPDATE webuser SET email=? WHERE email=?";
            $stmt2 = $database->prepare($sql2);
            $stmt2->execute([$email, $oldemail]);

            $error = '4'; // Sukses
        }

    } else {
        $error = '2'; // Konfirmasi password salah
    }

} else {
    $error = '3';
}


header("location: doctors.php?action=edit&error=" . $error . "&id=" . $id);

// File ini seharusnya tidak memiliki tag HTML setelah header()