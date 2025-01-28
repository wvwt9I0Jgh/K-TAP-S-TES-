<?php
session_start();

// Veritabanı bağlantısı (PDO)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=üyeler", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
    $sifre = trim($_POST['sifre']);
    if (!empty($email) && !empty($sifre)) {
        // Kullanıcı tablosunda kontrol et
        $kullanici_sorgu = $pdo->prepare("SELECT * FROM kullanicilar WHERE Email = :Email");
        $kullanici_sorgu->execute([':Email' => $email]);
        $kullanici = $kullanici_sorgu->fetch(PDO::FETCH_ASSOC);

        // Admin tablosunda kontrol et
        $admin_sorgu = $pdo->prepare("SELECT * FROM admin WHERE Email = :Email");
        $admin_sorgu->execute([':Email' => $email]);
        $admin = $admin_sorgu->fetch(PDO::FETCH_ASSOC);

        if ($kullanici && $sifre == $kullanici['sifre']) {
            // Kullanıcı giriş başarılı
            $_SESSION['Email'] = $kullanici['Email'];
            header("Location: anasayfa_üyeler.php");
            exit;
        } elseif ($admin && $sifre == $admin['sifre']) {
            // Admin giriş başarılı
            $_SESSION['Email'] = $admin['Email'];
            header("Location: anasayfa_admin.php");
            exit;
        } else {
            // Hatalı giriş
            $_SESSION['hata'] = "E-posta veya şifre hatalı!";
        }
    } else {
        $_SESSION['hata'] = "Lütfen tüm alanları doldurun!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Giriş</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-md">
    <div class="row">
        <div class="col">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark my-4">
                <a class="navbar-brand" href="anasayfa.php">Kitap Hup</a>
                <div class="collapse navbar-collapse" id="navbar_icerik">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="anasayfa.php">Anasayfa</a>
                        </li>
                    </ul>
                    <a href="üyegiriş.php" class="btn btn-outline-light me-2">Üye Giriş</a>
                    <a href="kayıt.php" class="btn btn-outline-light">Kayıt Ol</a>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Üye Giriş</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" name="Email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="sifre">Şifre</label>
                            <input type="password" name="sifre" id="sifre" class="form-control" required>
                        </div>

                        <?php if (isset($_SESSION['hata'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION['hata']; unset($_SESSION['hata']); ?>
                            </div>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>