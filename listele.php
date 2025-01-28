<?php
session_start();

// Veritabanı bağlantısı (PDO)
$host = 'localhost';
$dbname = 'üyeler';
$user = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Tür veya yazar seçimi
$filter = '';
$value = '';
if (isset($_GET['turu'])) {
    $filter = 'turu';
    $value = $_GET['turu'];
} elseif (isset($_GET['yazar'])) {
    $filter = 'yazar';
    $value = $_GET['yazar'];
}

// Kitapları filtreleme
$book_sql = "SELECT * FROM kitaplar WHERE $filter = :value";
$stmt = $pdo->prepare($book_sql);
$stmt->execute(['value' => $value]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Listesi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-black text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold">
                <a href="anasayfa_admin.php" class="text-white">Kitap Hup</a>
            </div>
            <nav class="flex space-x-4">
                <a href="anasayfa.php" class="text-white hover:text-gray-300">Ana Sayfa</a>
                <a href="anasayfa.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Çıkış</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold mb-4">Kitap Listesi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            if (!empty($books)) {
                foreach ($books as $book) {
                    $book_img = htmlspecialchars($book['kitap_resmi']);
                    $book_title = htmlspecialchars($book['kitap']);
                    $book_author = htmlspecialchars($book['yazar']);
                    $book_pages = htmlspecialchars($book['sayfa_sayisi']);
                    $book_genre = htmlspecialchars($book['turu']);
                    $book_id = htmlspecialchars($book['kitap_no']);
            ?>
            <div class="group bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative">
                    <img src="/üyeler/imgs/<?php echo $book_img; ?>" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo $book_title; ?>">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $book_title; ?></h3>
                    <div class="space-y-2 text-gray-600">
                        <p class="flex items-center">
                            <i class="fas fa-user-edit w-6"></i>
                            <span><?php echo $book_author; ?></span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-book-open w-6"></i>
                            <span><?php echo $book_pages; ?> Sayfa</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-bookmark w-6"></i>
                            <span><?php echo $book_genre; ?></span>
                        </p>
                    </div>
                    <a href="kitapaçıklaması.php?id=<?php echo $book_id; ?>" 
                       class="mt-4 inline-block w-full text-center bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition-colors duration-300">
                        Daha Fazla
                    </a>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-gray-600 text-center'>Kitap bulunamadı.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>