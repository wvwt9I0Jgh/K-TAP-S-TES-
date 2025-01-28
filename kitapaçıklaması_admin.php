<?php
// Veritabanı bağlantısı ve gerekli sorgular burada yapılmalı
$book_id = $_GET['id'];
$pdo = new PDO("mysql:host=localhost;dbname=üyeler", "root", ""); // Kullanıcı adı ve şifreyi ekleyin
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$book_stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE kitap_no = :id");
$book_stmt->execute(['id' => $book_id]);
$book = $book_stmt->fetch(PDO::FETCH_ASSOC);

$book_img = $book['kitap_resmi'];
$book_title = $book['kitap'];
$book_author = $book['yazar'];
$book_genre = $book['turu'];
$book_pages = $book['sayfa_sayisi'];
$book_publish_date = $book['cikis_tarihi'];

$other_books_stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE yazar = :yazar AND kitap_no != :id");
$other_books_stmt->execute(['yazar' => $book_author, 'id' => $book_id]);
$other_books_result = $other_books_stmt->fetchAll(PDO::FETCH_ASSOC);

$genres_stmt = $pdo->query("SELECT DISTINCT turu FROM kitaplar");
$genres_result = $genres_stmt->fetchAll(PDO::FETCH_ASSOC);

$authors_stmt = $pdo->query("SELECT DISTINCT yazar FROM kitaplar");
$authors_result = $authors_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Detayları</title>
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
                        <a href="anasayfa.php" class="text-sm hover:text-gray-300">Çıkış</a>
                        <a href="kitaplistedeneme.php" class="text-sm hover:text-gray-300">Admin Panel Giriş</a>
                    </div>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <div class="container mx-auto p-4">
                <nav class="py-6 px-4">
                    <div class="flex justify-between items-center">
                        <a href="anasayfa_admin.php" class="text-2xl font-bold tracking-wider">KITAP<span class="text-gray-400">HUB</span></a>
                        
                        <div class="hidden md:flex space-x-8">
                            <a href="anasayfa_admin.php" class="hover:text-gray-300 transition-colors">Anasayfa</a>
                            
                            <div class="relative group">
                                <button class="hover:text-gray-300 transition-colors">Türler</button>
                                <div class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <?php
                                    if (count($genres_result) > 0) {
                                        foreach ($genres_result as $genre) {
                                            echo "<a href='liste_admin.php?turu=" . urlencode($genre['turu']) . "' class='block px-4 py-2 hover:bg-gray-100'>" . htmlspecialchars($genre['turu']) . "</a>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="relative group">
                                <button class="hover:text-gray-300 transition-colors">Yazarlar</button>
                                <div class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <?php
                                    if (count($authors_result) > 0) {
                                        foreach ($authors_result as $author) {
                                            echo "<a href='liste_admin.php?yazar=" . urlencode($author['yazar']) . "' class='block px-4 py-2 hover:bg-gray-100'>" . htmlspecialchars($author['yazar']) . "</a>";
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

    <div class="container mx-auto mt-4">
        <!-- Kitap Detayları -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <img src="/üyeler/imgs/<?php echo htmlspecialchars($book_img); ?>" class="w-4000 h-4000 object-cover" alt="<?php echo htmlspecialchars($book_title); ?>">
            </div>
            <div>
                <h2 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($book_title); ?></h2>
                <p class="text-lg"><strong>Yazar:</strong> <?php echo htmlspecialchars($book_author); ?></p>
                <p class="text-lg"><strong>Tür:</strong> <?php echo htmlspecialchars($book_genre); ?></p>
                <p class="text-lg"><strong>Sayfa Sayısı:</strong> <?php echo htmlspecialchars($book_pages); ?></p>
                <p class="text-lg"><strong>Basım Tarihi:</strong> <?php echo htmlspecialchars($book_publish_date); ?></p>
            </div>
        </div>
        
        <!-- Aynı Yazarın Diğer Kitapları -->
        <div class="mt-8">
            <h3 class="text-2xl font-bold mb-4">Aynı Yazarın Diğer Kitapları</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php
                if (count($other_books_result) > 0) {
                    foreach ($other_books_result as $other_book) {
                        $other_book_img = htmlspecialchars($other_book['kitap_resmi']);
                        $other_book_title = htmlspecialchars($other_book['kitap']);
                        $other_book_id = htmlspecialchars($other_book['kitap_no']);
                ?>
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="relative">
                        <img src="/üyeler/imgs/<?php echo $other_book_img; ?>" class="w-4000 h-4000 object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo $other_book_title; ?>">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h5 class="text-xl font-bold text-gray-800 mb-2"><?php echo $other_book_title; ?></h5>
                        <a href="kitapaçıklaması.php?id=<?php echo $other_book_id; ?>" class="mt-4 inline-block w-full text-center bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition-colors duration-300">Daha Fazla</a>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p class='text-gray-600'>Yazarın başka kitabı bulunmamaktadır.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-white pt-12 pb-6 mt-8">
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