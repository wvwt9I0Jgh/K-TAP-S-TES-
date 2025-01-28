<?php
// Veritabanı bağlantısı ve gerekli sorgular burada yapılmalı
$pdo = new PDO("mysql:host=localhost;dbname=üyeler", "root", ""); // Kullanıcı adı ve şifreyi ekleyin
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['kitap_no'])) {
    $kitap_no = $_GET['kitap_no'];
    $stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE kitap_no = :kitap_no");
    $stmt->execute(['kitap_no' => $kitap_no]);
    $kitap = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kitap) {
        die("Kitap bulunamadı.");
    }
} else {
    die("Geçersiz kitap numarası.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kitap_ad = $_POST['kitap'];
    $yazar = $_POST['yazar'];
    $sayfa_sayisi = $_POST['sayfa_sayisi'];
    $turu = $_POST['turu'];
    $cikis_tarihi = $_POST['cikis_tarihi'];
    $kitap_resim = $kitap['kitap_resmi']; // Varsayılan olarak mevcut resmi kullan

    // Yeni resim yüklenmişse
    if (isset($_FILES['kitap_resmi']) && $_FILES['kitap_resmi']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/imgs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['kitap_resmi']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['kitap_resmi']['tmp_name'], $target_file)) {
            $kitap_resim = $file_name;
        }
    }

    $stmt = $pdo->prepare("UPDATE kitaplar SET kitap = :kitap, yazar = :yazar, sayfa_sayisi = :sayfa_sayisi, turu = :turu, cikis_tarihi = :cikis_tarihi, kitap_resmi = :kitap_resmi WHERE kitap_no = :kitap_no");
    $stmt->execute([
        'kitap' => $kitap_ad,
        'yazar' => $yazar,
        'sayfa_sayisi' => $sayfa_sayisi,
        'turu' => $turu,
        'cikis_tarihi' => $cikis_tarihi,
        'kitap_resmi' => $kitap_resim,
        'kitap_no' => $kitap_no
    ]);

    echo "Kitap başarıyla güncellendi.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Güncelle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold mb-4">Kitap Güncelle</h2>
        <form method="post" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="kitap" class="block text-gray-700">Kitap Adı</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="kitap" name="kitap" value="<?php echo htmlspecialchars($kitap['kitap']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="yazar" class="block text-gray-700">Yazar</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="yazar" name="yazar" value="<?php echo htmlspecialchars($kitap['yazar']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="sayfa_sayisi" class="block text-gray-700">Sayfa Sayısı</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="sayfa_sayisi" name="sayfa_sayisi" value="<?php echo htmlspecialchars($kitap['sayfa_sayisi']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="turu" class="block text-gray-700">Türü</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="turu" name="turu" value="<?php echo htmlspecialchars($kitap['turu']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="cikis_tarihi" class="block text-gray-700">Çıkış Tarihi</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="cikis_tarihi" name="cikis_tarihi" value="<?php echo htmlspecialchars($kitap['cikis_tarihi']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="kitap_resim" class="block text-gray-700">Kitap Resmi</label>
                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="kitap_resim" name="kitap_resmi">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Kitap Güncelle</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>