<?php
$host = "localhost";  // Veritabanı sunucusu
$user = "root";       // Veritabanı kullanıcı adı
$pass = "";           // Veritabanı şifresi
$dbname = "üyeler"; // Veritabanı adı

// Bağlantıyı kurma
$conn = new mysqli($host, $user, $pass, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>
