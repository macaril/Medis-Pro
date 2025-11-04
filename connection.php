<?php
$servername = "aws-1-ap-southeast-2.pooler.supabase.com"; 
$port = "5432";

// PERBAIKAN DI SINI: Ganti "postgres" menjadi "postgres.wjbojmcwodjhnywsgfvp"
$username = "postgres.wjbojmcwodjhnywsgfvp"; 
$password = "260703Lutfiardiansyah"; 
$dbname = "postgres"; 

$dsn = "pgsql:host=$servername;port=$port;dbname=$dbname"; 

try {
    $database = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // Jika koneksi berhasil, Anda mungkin ingin mencetak sesuatu untuk konfirmasi
    // echo "Koneksi Supabase Berhasil!";
} catch (PDOException $e) {
    die("Koneksi Gagal (PostgreSQL): " . $e->getMessage());
}