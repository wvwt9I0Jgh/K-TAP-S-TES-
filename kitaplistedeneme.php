<?php
// Veritabanı bağlantısı
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "üyeler";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Kitap ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['kitap_ekle'])) {
    $kitap_resim = 'default.png'; // Default image if no image is uploaded

    // Check if a file is uploaded
    if (isset($_FILES['kitap_resmi']) && $_FILES['kitap_resmi']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/img/'; // Resimlerin kaydedileceği dizin

        // Dizin mevcut değilse oluştur
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['kitap_resmi']['name']);
        $target_file = $upload_dir . $file_name;

        // Resim türü kontrolü kaldırıldı, tüm dosyalara izin verildi
        if (move_uploaded_file($_FILES['kitap_resmi']['tmp_name'], $target_file)) {
            $kitap_resim = $file_name;
        } else {
            echo "<div class='alert alert-danger'>Resim yükleme başarısız.</div>";
        }
    }

    // Veritabanına ekleme
    $stmt = $conn->prepare("INSERT INTO kitaplar (kitap, yazar, sayfa_sayisi, turu, cikis_tarihi, kitap_resmi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $kitap, $yazar, $sayfa_sayisi, $turu, $cikis_tarihi, $kitap_resim);

    $kitap = $_POST['kitap'];
    $yazar = $_POST['yazar'];
    $sayfa_sayisi = $_POST['sayfa_sayisi'];
    $turu = $_POST['turu'];
    $cikis_tarihi = $_POST['cikis_tarihi'];

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Kitap başarıyla eklendi.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Kitap silme işlemi
if (isset($_GET['sil'])) {
    $kitap_no = $_GET['sil'];
    $stmt = $conn->prepare("DELETE FROM kitaplar WHERE kitap_no = ?");
    $stmt->bind_param("i", $kitap_no);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Kitap başarıyla silindi.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Kitapları listeleme
$sql = "SELECT kitap_no, kitap, yazar, sayfa_sayisi, turu, cikis_tarihi, kitap_resmi FROM kitaplar";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-black text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold">
                <a href="anasayfa_admin.php" class="text-white">Kitap Hup</a>
            </div>
            <nav class="flex space-x-4">
                <a href="anasayfa_admin.php" class="text-white hover:text-gray-300">Ana Sayfa</a>
                <a href="anasayfa.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Çıkış</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold mb-4">Kitap Listesi</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Kitap No</th>
                    <th class="py-2 px-4 border-b">Kitap Adı</th>
                    <th class="py-2 px-4 border-b">Yazar</th>
                    <th class="py-2 px-4 border-b">Sayfa Sayısı</th>
                    <th class="py-2 px-4 border-b">Türü</th>
                    <th class="py-2 px-4 border-b">Çıkış Tarihi</th>
                    <th class="py-2 px-4 border-b">Kitap Resmi</th>
                    <th class="py-2 px-4 border-b">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resim_yolu = !empty($row['kitap_resmi']) ? 'img/' . htmlspecialchars($row['kitap_resmi']) : 'img/default.png';
                        echo "<tr>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['kitap_no']) . "</td>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['kitap']) . "</td>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['yazar']) . "</td>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['sayfa_sayisi']) . "</td>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['turu']) . "</td>
                                <td class='py-2 px-4 border-b'>" . htmlspecialchars($row['cikis_tarihi']) . "</td>
                                <td class='py-2 px-4 border-b'><img src='" . $resim_yolu . "' alt='Kitap Resmi' class='w-12 h-auto'></td>
                                <td class='py-2 px-4 border-b'>
                                    <a href='?sil=" . htmlspecialchars($row['kitap_no']) . "' class='bg-red-500 text-white px-2 py-1 rounded'>Sil</a>
                                    <a href='duzenle.php?kitap_no=" . htmlspecialchars($row['kitap_no']) . "' class='bg-yellow-500 text-white px-2 py-1 rounded'>Düzenle</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='py-2 px-4 border-b text-center'>Kitap bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="text-2xl font-bold mt-8 mb-4">Yeni Kitap Ekle</h2>
        <form method="post" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <input type="hidden" name="kitap_ekle" value="1">
            <div class="mb-4">
                <label for="kitap" class="block text-gray-700">Kitap Adı</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="kitap" name="kitap" required>
            </div>
            <div class="mb-4">
                <label for="yazar" class="block text-gray-700">Yazar</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="yazar" name="yazar" required>
            </div>
            <div class="mb-4">
                <label for="sayfa_sayisi" class="block text-gray-700">Sayfa Sayısı</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="sayfa_sayisi" name="sayfa_sayisi" required>
            </div>
            <div class="mb-4">
                <label for="turu" class="block text-gray-700">Türü</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="turu" name="turu" required>
            </div>
            <div class="mb-4">
                <label for="cikis_tarihi" class="block text-gray-700">Çıkış Tarihi</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="cikis_tarihi" name="cikis_tarihi" required>
            </div>
            <div class="mb-4">
                <label for="kitap_resim" class="block text-gray-700">Kitap Resmi</label>
                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg" id="kitap_resim" name="kitap_resmi">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Kitap Ekle</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>