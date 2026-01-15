<?php

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}


if ($_GET) {
    //import database
    include("../connection.php");
    $id = $_GET["id"];

    // LANGKAH 1: Ambil email dokter (Gunakan Prepared Statement agar aman)
    $sql1 = "SELECT docemail FROM doctor WHERE docid = ?";
    $stmt1 = $database->prepare($sql1);
    $stmt1->execute([$id]);
    
    // PERBAIKAN UTAMA: Mengganti fetch_assoc() menjadi fetch(PDO::FETCH_ASSOC)
    $result = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $email = $result["docemail"];

        // LANGKAH 2: Hapus dari tabel webuser (Login)
        $sql2 = "DELETE FROM webuser WHERE email = ?";
        $stmt2 = $database->prepare($sql2);
        $stmt2->execute([$email]);

        // LANGKAH 3: Hapus dari tabel doctor (Data Profil)
        $sql3 = "DELETE FROM doctor WHERE docemail = ?";
        $stmt3 = $database->prepare($sql3);
        $stmt3->execute([$email]);
    }

    header("location: doctors.php");
}

?>