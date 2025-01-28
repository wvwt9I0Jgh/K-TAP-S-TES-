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

// Kitapları çekme (en yeni eklenene göre sıralama)
$book_sql = "SELECT * FROM kitaplar ORDER BY kitap_no DESC";
$book_result = $pdo->query($book_sql);

// Türleri çekme
$genres_sql = "SELECT DISTINCT turu FROM kitaplar";
$genres_result = $pdo->query($genres_sql);

// Yazarları çekme
$authors_sql = "SELECT DISTINCT yazar FROM kitaplar";
$authors_result = $pdo->query($authors_sql);

// Arama işlemi
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = trim($_POST['search']);
    if (!empty($search_query)) {
        $stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE kitap LIKE :search_query ORDER BY kitap_no DESC");
        $stmt->execute([':search_query' => '%' . $search_query . '%']);
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-black text-white">
        <div class="max-w-7xl mx-auto">
            <!-- Top Bar -->
            <div class="border-b border-gray-800 py-2">
                <div class="flex justify-between items-center px-4">
                    <p class="text-sm">Hoşgeldiniz! En sevdiğiniz kitapları keşfedin.</p>
                    <div class="flex space-x-4">
                        <a href="kitaplistedeneme.php" class="text-sm hover:text-gray-300">Admin Panel</a>
                        <a href="anasayfa.php" class="text-sm hover:text-gray-300">Çıkış</a>
                    </div>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <div class="container mx-auto p-4">
                <nav class="py-6 px-4">
                    <div class="flex justify-between items-center">
                        <a href="anasayfa.php" class="text-2xl font-bold tracking-wider">KITAP<span class="text-gray-400">HUB</span></a>
                        
                        <div class="hidden md:flex space-x-8">
                            <a href="anasayfa.php" class="hover:text-gray-300 transition-colors">Anasayfa</a>
                            
                            <div class="relative group">
                                <button class="hover:text-gray-300 transition-colors">Türler</button>
                                <div class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <?php
                                    if ($genres_result->rowCount() > 0) {
                                        while ($genre = $genres_result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<a href='listele.php?turu=" . urlencode($genre['turu']) . "' class='block px-4 py-2 hover:bg-gray-100'>" . htmlspecialchars($genre['turu']) . "</a>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="relative group">
                                <button class="hover:text-gray-300 transition-colors">Yazarlar</button>
                                <div class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <?php
                                    if ($authors_result->rowCount() > 0) {
                                        while ($author = $authors_result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<a href='listele.php?yazar=" . urlencode($author['yazar']) . "' class='block px-4 py-2 hover:bg-gray-100'>" . htmlspecialchars($author['yazar']) . "</a>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Arama Kutusu -->
                        <div class="relative w-64">
                            <form method="POST" action="" class="relative">
                                <input type="text" name="search" placeholder="Kitap ara..." class="bg-gray-900 text-white rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-600 w-full">
                                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-search text-gray-400"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section with Carousel -->
    <div class="relative h-96 overflow-hidden">
        <div class="flex transition-transform duration-500" id="carousel">
            <img src="indir (1).jpg" class="w-full h-96 object-cover brightness-75" alt="Kitap 1">
            <img src="indir (2).jpg" class="w-full h-96 object-cover brightness-75" alt="Kitap 2">
            <img src="indir (3).jpg" class="w-full h-96 object-cover brightness-75" alt="Kitap 3">
            <img src="indir (4).jpg" class="w-full h-96 object-cover brightness-75" alt="Kitap 4">
        </div>
        <div class="absolute inset-0 flex items-center justify-between px-8">
            <button onclick="moveSlide(-1)" class="bg-black bg-opacity-50 p-3 rounded-full hover:bg-opacity-75 transition-all">
                <i class="fas fa-chevron-left text-white"></i>
            </button>
            <button onclick="moveSlide(1)" class="bg-black bg-opacity-50 p-3 rounded-full hover:bg-opacity-75 transition-all">
                <i class="fas fa-chevron-right text-white"></i>
            </button>
        </div>
    </div>
    <!-- Slider (Carousel) Bitişi -->

    <!-- Kitap Kartları -->
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            if ($book_result->rowCount() > 0) {
                while ($row = $book_result->fetch(PDO::FETCH_ASSOC)) {
                    $book_img = htmlspecialchars($row['kitap_resmi']);
                    $book_title = htmlspecialchars($row['kitap']);
                    $book_author = htmlspecialchars($row['yazar']);
                    $book_pages = htmlspecialchars($row['sayfa_sayisi']);
                    $book_genre = htmlspecialchars($row['turu']);
                    $book_id = htmlspecialchars($row['kitap_no']);
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
                    <a href="kitapaçıklaması_admin.php?id=<?php echo $book_id; ?>" 
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

    <!-- Footer -->
    <footer class="bg-black text-white pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h4 class="text-xl font-bold mb-4">Hakkımızda</h4>
                    <p class="text-gray-400">KitapHub, kitapseverlerin buluşma noktası. En sevdiğiniz kitapları keşfedin, okuyun ve paylaşın.</p>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4">Hızlı Linkler</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">En Çok Okunanlar</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Yeni Çıkanlar</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Yazarlar</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kategoriler</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4">İletişim</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope w-6"></i>
                            <span>info@kitaphub.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone w-6"></i>
                            <span>+90 555 123 4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            <span>İstanbul, Türkiye</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-gray-400">
                <p>&copy; 2024 KitapHub. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('#carousel img');
        const totalSlides = slides.length;

        function moveSlide(direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            document.getElementById('carousel').style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        setInterval(() => moveSlide(1), 5000);
    </script>
</body>
</html>