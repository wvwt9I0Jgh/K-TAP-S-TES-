<?php
// Veritabanı bağlantısı
$host = 'localhost';
$user = 'root'; // Kullanıcı adı
$password = ''; // Şifre
$dbname = 'üyeler'; // Veritabanı adı

// Bağlantıyı oluşturma
$conn = new mysqli($host, $user, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// Kitap verilerini çekme
$book_sql = "SELECT * FROM kitaplar";
$book_result = $conn->query($book_sql);

// Türleri ve yazarları çekme
$genres_sql = "SELECT DISTINCT turu FROM kitaplar";
$genres_result = $conn->query($genres_sql);

$authors_sql = "SELECT DISTINCT yazar FROM kitaplar";
$authors_result = $conn->query($authors_sql);

// Veritabanı bağlantısını kapatma
$conn->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kitap Hup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <div class="container-md">
  <div class="row row-cols-1">
    <div class="col">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark my-4">
        <a class="navbar-brand" href="anasayfa.php">Kitap Hup</a> <!-- Anasayfa'ya link atıldı -->
        <a class="navbar-brand" href="anasayfa.php">Anasayfa</a>

        <!-- Açılır Menü Butonları -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_icerik" aria-controls="navbar_icerik" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbar_icerik">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="anasayfa.php">Anasayfa</a> <!-- Anasayfa linki -->
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGenre" role="button" data-bs-toggle="dropdown" aria-expanded="false">Türler</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownGenre">
                <?php
                // Türleri listele
                if ($genres_result->num_rows > 0) {
                    while ($genre = $genres_result->fetch_assoc()) {
                        echo "<li><a class='dropdown-item' href='#'>" . $genre['turu'] . "</a></li>";
                    }
                }
                ?>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAuthor" role="button" data-bs-toggle="dropdown" aria-expanded="false">Yazarlar</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownAuthor">
                <?php
                // Yazarları listele
                if ($authors_result->num_rows > 0) {
                    while ($author = $authors_result->fetch_assoc()) {
                        echo "<li><a class='dropdown-item' href='#'>" . $author['yazar'] . "</a></li>";
                    }
                }
                ?>
              </ul>
            </li>
          </ul>

        </div>
      </nav>
    </div>
  </div>
</div>


      <!-- Slider (Carousel) Başlangıcı -->
      <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <!-- Slider 1 -->
          <div class="carousel-item active">
            <img src="indir (1).jpg" class="d-block w-100" alt="Kitap Resmi 1">
          </div>
          <!-- Slider 2 -->
          <div class="carousel-item">
            <img src="indir (2).jpg" class="d-block w-100" alt="Kitap Resmi 2">
          </div>
          <!-- Slider 3 -->
          <div class="carousel-item">
            <img src="indir (3).jpg" class="d-block w-100" alt="Kitap Resmi 3">
          </div>
          <!-- Slider 4 -->
          <div class="carousel-item">
            <img src="indir (4).jpg" class="d-block w-100" alt="Kitap Resmi 4">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <!-- Slider (Carousel) Bitişi -->

      <!-- Kitap Kartları -->
      <div class="container">
        <div class="row row-cols-1 row-cols-md-3">
          <?php
          // Kitapları listeleme ve kartları oluşturma
          if ($book_result->num_rows > 0) {
              while ($row = $book_result->fetch_assoc()) {
                $book_img = $row['kitap_resmi']; // Kitap resminin URL'si
                $book_title = $row['kitap']; // Kitap başlığı
                $book_author = $row['yazarı']; // Kitap yazarı
                $book_pages = $row['sayfa_sayisi']; // Sayfa sayısı
                $book_genre = $row['turu']; // Türü
                $book_id = $row['kitap_no']; // Kitap ID'si (buton linki için)
                
          ?>
            <div class="col mb-4">
              <div class="card h-100">
                <!-- Kitap Resmi -->
                <img src="<?php echo $book_img; ?>" class="card-img-top" alt="<?php echo $book_title; ?>">
                <div class="card-body">
                  <!-- Kitap Başlığı -->
                  <h5 class="card-title"><?php echo $book_title; ?></h5>
                  <!-- Kitap Yazar, Sayfa Sayısı ve Türü -->
                  <p class="card-text">Yazar: <?php echo $book_author; ?></p>
                  <p class="card-text">Sayfa Sayısı: <?php echo $book_pages; ?></p>
                  <p class="card-text">Tür: <?php echo $book_genre; ?></p>
                  <!-- Daha Fazla Butonu -->
                  <a href="kitapaçıklaması.php?id=<?php echo $book_id; ?>" class="btn btn-primary">Daha Fazla</a>
                </div>
              </div>
            </div>
          <?php
              }
          } else {
              echo "<p>Kitap bulunamadı.</p>";
          }
          ?>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
