<?php
include("baglanti.php");

if(isset($_POST["giris"])) {
    $email = $_POST["Email"];
    $sifre = $_POST["sifre"];

    // Add your login logic here
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Girişi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-black text-white">
        <nav class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <a href="anasayfa.php" class="text-2xl font-bold tracking-wider">KITAP<span class="text-gray-400">HUB</span></a>
                    <a href="anasayfa.php" class="hover:text-gray-300 transition-colors">Anasayfa</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="üyegiriş.php" class="bg-white text-black px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">Üye Giriş</a>
                    <a href="kayıt.php" class="text-gray-300 hover:text-white transition-colors">Kayıt Ol</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Login Form -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900">Hoş Geldiniz</h2>
                <p class="mt-2 text-sm text-gray-600">Hesabınıza giriş yapın</p>
            </div>

            <form class="space-y-6" action="üyegiriş.php" method="POST">
                <div>
                    <label for="Email" class="block text-sm font-medium text-gray-700">Email adresi</label>
                    <input type="email" name="Email" id="Email" required 
                           class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                </div>

                <div>
                    <label for="sifre" class="block text-sm font-medium text-gray-700">Şifre</label>
                    <input type="password" name="sifre" id="sifre" required 
                           class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" name="remember-me" 
                               class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Beni hatırla
                        </label>
                    </div>

                    <a href="#" class="text-sm text-black hover:text-gray-800">
                        Şifremi unuttum
                    </a>
                </div>

                <button type="submit" name="giris"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200">
                    Giriş Yap
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">veya</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Hesabınız yok mu?
                        <a href="kayıt.php" class="font-medium text-black hover:text-gray-800">
                            Hemen kaydolun
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>