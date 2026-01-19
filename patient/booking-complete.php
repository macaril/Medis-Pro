<?php
// =======================================================
// DEBUGGING AKTIF: BARIS INI WAJIB ADA SAAT MENGUJI
error_reporting(E_ALL);
ini_set('display_errors', 1);
// =======================================================


session_start();

if (isset($_SESSION["user"])) {
  if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
  } else {
    $useremail = $_SESSION["user"];
  }
} else {
  header("location: ../login.php");
  exit();
}



//import database
include("../connection.php");


// PERBAIKAN: Konversi kueri pengambilan user dari MySQLi ke PDO
$sqlmain = "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);

try {
  $stmt->execute([$useremail]);
  $userfetch = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$userfetch) {
    die("ERROR: Patient Data Not Found (useremail: $useremail)");
  }

  $userid = $userfetch["pid"];
  $username = $userfetch["pname"];
} catch (PDOException $e) {
  die("Database Error (User Fetch): " . $e->getMessage());
}




if ($_POST) {
  if (isset($_POST["booknow"])) {

    $apponum = $_POST["apponum"];
    $scheduleid = $_POST["scheduleid"];
    $date = $_POST["date"];

    // PERBAIKAN: Gunakan blok try-catch untuk operasi INSERT
    try {
      // --- TAMBAHAN LOGIKA VALIDASI KUOTA (MULAI) ---

      // 1. Ambil batas maksimal (NOP) dari tabel schedule
      $stmtLimit = $database->prepare("SELECT nop, title FROM schedule WHERE scheduleid = ?");
      $stmtLimit->execute([$scheduleid]);
      $scheduleData = $stmtLimit->fetch(PDO::FETCH_ASSOC);

      if (!$scheduleData) {
        die("Jadwal tidak ditemukan.");
      }

      $max_patients = $scheduleData['nop'];
      $session_title = $scheduleData['title'];

      // 2. Hitung jumlah pasien yang SUDAH terdaftar di jadwal ini (Real-time count)
      $stmtCount = $database->prepare("SELECT count(*) as total FROM appointment WHERE scheduleid = ?");
      $stmtCount->execute([$scheduleid]);
      $countData = $stmtCount->fetch(PDO::FETCH_ASSOC);
      $current_bookings = $countData['total'];

      // 3. Bandingkan: Jika sudah penuh, batalkan proses
      if ($current_bookings >= $max_patients) {
        // Opsional: Redirect kembali dengan pesan error
        echo "<script>
                    alert('Mohon maaf, kuota untuk sesi $session_title sudah penuh (Maksimal $max_patients pasien).');
                    window.location.href = 'schedule.php';
                  </script>";
        exit();
      }
      // Lanjutkan proses INSERT jika kuota aman
      $sql2 = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) VALUES (?, ?, ?, ?)";
      $stmt_insert = $database->prepare($sql2);

      // Eksekusi Prepared Statement
      $stmt_insert->execute([$userid, $apponum, $scheduleid, $date]);

      header("location: appointment.php?action=booking-added&id=" . $apponum . "&titleget=none");
      exit();
    } catch (PDOException $e) {
      // Tampilkan pesan error database jika INSERT gagal
      die("8. BOOKING FAILED! Database Error: " . $e->getMessage());
    }
  }
}

die("9. Script Finished without POST. (Ini tidak seharusnya terlihat saat klik tombol)");
