<?php
// app/Views/partials/header.php

$pageTitle = isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME . ' - Türkeli Haber & Sosyal Platform';

// Check login status from session
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? ($_SESSION['user_fullname'] ?? $_SESSION['username']) : null; // Prefer full name if available

?>
<!DOCTYPE html>
<html lang="tr" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <style>
        /* Lucide Icons Font */
        @font-face { font-family: 'LucideIcons'; src: url(https://cdn.jsdelivr.net/npm/lucide-static@0.473.0/font/lucide.ttf) format('truetype'); }
        .lucide { font-family: 'LucideIcons'; font-size: 1.25rem; line-height: 1; vertical-align: middle; display: inline-block; }
        /* Base Styles */
        body { font-family: 'Inter', sans-serif; @apply bg-gray-50 text-gray-800 transition-colors duration-300; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        /* Dark Mode Styles */
        .dark body { @apply bg-gray-900 text-gray-200; }
        .dark header, .dark footer, .dark .bg-white { @apply bg-gray-800 text-gray-200 border-gray-700; }
        .dark .bg-gray-50 { @apply bg-gray-900; } .dark .bg-gray-100 { @apply bg-gray-700; } .dark .bg-gray-200 { @apply bg-gray-600; } .dark .bg-gray-300 { @apply bg-gray-500; }
        .dark .text-gray-800, .dark .text-gray-900 { @apply text-gray-100; } .dark .text-gray-700 { @apply text-gray-200; } .dark .text-gray-600, .dark .text-gray-500 { @apply text-gray-400; }
        .dark .shadow-md { @apply shadow-lg shadow-gray-900/50; } .dark .border-gray-200 { @apply border-gray-700; } .dark .border-gray-300 { @apply border-gray-600; }
        .dark .hover\:bg-gray-50:hover { @apply bg-gray-700; } .dark .hover\:bg-gray-100:hover { @apply bg-gray-700; } .dark .hover\:bg-gray-300:hover { @apply bg-gray-500; }
        .dark .hover\:text-teal-700:hover { @apply text-teal-400; } .dark .text-teal-700 { @apply text-teal-400; } .dark .bg-teal-100 { @apply bg-teal-900 bg-opacity-50; } .dark .text-teal-600 { @apply text-teal-400; } .dark .bg-teal-600 { @apply bg-teal-700; } .dark .hover\:bg-teal-700:hover { @apply bg-teal-600; } .dark .border-teal-600 { @apply border-teal-500; }
        .dark .focus\:ring-teal-500:focus { --tw-ring-color: theme('colors.teal.600'); } .dark .form-radio:checked { border-color: theme('colors.teal.500'); background-color: theme('colors.teal.500'); }
        /* Transitions & Active Nav */
        a, button, input, textarea { transition: all 0.2s ease-in-out; }
        .nav-active { @apply text-teal-600 font-semibold; } .dark .nav-active { @apply text-teal-400; }
        /* Comment Styles */
        .comment { @apply mb-4 p-4 border border-gray-200 rounded-lg; } .comment-meta { @apply text-xs text-gray-500 mb-1; } .comment-actions { @apply text-xs space-x-3 mt-2; } .comment-actions a { @apply text-gray-500 hover:text-teal-600; } .comment-reply { @apply ml-8 mt-3 pl-4 border-l-2 border-gray-200; }
        .dark .comment { @apply border-gray-700; } .dark .comment-reply { @apply border-l-2 border-gray-600; } .dark .comment-actions a { @apply text-gray-400 hover:text-teal-400; }
        /* Ad Placeholder Styles */
        .ad-placeholder { @apply bg-gray-200 dark:bg-gray-700 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-medium p-4 text-center; min-height: 5rem; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?php echo BASE_URL; ?>images/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>images/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo BASE_URL; ?>manifest.json">
    <meta name="theme-color" content="#0D9488">
</head>
<body class="antialiased">

<header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold text-teal-700 dark:text-teal-400 flex items-center flex-shrink-0">
            <span class="lucide mr-2">&#xe8b1;</span> <?php echo SITE_NAME; ?>
        </a>
        <nav class="hidden lg:flex space-x-5 items-center flex-grow justify-center">
            <a href="<?php echo BASE_URL; ?>?page=home" class="text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium flex items-center <?php echo ($page == 'home' ? 'nav-active' : ''); ?>"><span class="lucide mr-1 text-base">&#xe86d;</span>Ana Sayfa</a>
            <a href="<?php echo BASE_URL; ?>?page=haberler" class="text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium flex items-center <?php echo ($page == 'haberler' ? 'nav-active' : ''); ?>"><span class="lucide mr-1 text-base">&#xe8d0;</span>Haberler</a>
            <a href="<?php echo BASE_URL; ?>?page=rehber" class="text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium flex items-center <?php echo ($page == 'rehber' ? 'nav-active' : ''); ?>"><span class="lucide mr-1 text-base">&#xe8a8;</span>Rehber</a>
            <a href="<?php echo BASE_URL; ?>?page=etkinlikler" class="text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium flex items-center <?php echo ($page == 'etkinlikler' ? 'nav-active' : ''); ?>"><span class="lucide mr-1 text-base">&#xe7ee;</span>Etkinlikler</a>
            <a href="<?php echo BASE_URL; ?>?page=forum" class="text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium flex items-center <?php echo ($page == 'forum' ? 'nav-active' : ''); ?>"><span class="lucide mr-1 text-base">&#xe9a4;</span>Forum</a>
        </nav>

        <div class="flex items-center space-x-3 flex-shrink-0">
            <button id="dark-mode-toggle" class="text-gray-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Koyu Modu Aç/Kapat" aria-pressed="false">
                <span class="lucide sun-icon">&#xe97f;</span> <span class="lucide moon-icon hidden">&#xe8ab;</span>
            </button>
            <button class="text-gray-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Ara">
                <span class="lucide">&#xe951;</span>
            </button>

            <?php // ----- UPDATED: Show Login/Logout based on session ----- ?>
            <?php if ($isLoggedIn): ?>

                <div class="relative hidden sm:inline-block" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-teal-700 dark:hover:text-teal-400">
                        <span class="lucide text-xl">&#xe9a9;</span>
                        <span class="truncate max-w-[100px]"><?php echo htmlspecialchars($username); ?></span>
                        <span class="lucide text-xs transition-transform duration-200" :class="{'rotate-180': open}">&#xe7c0;</span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50" style="display: none;">
                        <a href="<?php echo BASE_URL; ?>?page=profil" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Profilim</a>
                        <a href="<?php echo BASE_URL; ?>?page=cikis-yap" class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">Çıkış Yap</a>
                    </div>
                </div>

                <a href="<?php echo BASE_URL; ?>?page=cikis-yap" class="sm:hidden text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Çıkış Yap">
                    <span class="lucide text-xl">&#xe89e;</span>
                </a>
            <?php else: ?>

                <a href="<?php echo BASE_URL; ?>?page=giris" class="hidden sm:flex items-center space-x-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-teal-700 dark:hover:text-teal-400" aria-label="Giriş Yap veya Kayıt Ol">
                    <span class="lucide text-xl">&#xe89d;</span>
                    <span>Giriş Yap</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?page=giris" class="sm:hidden text-gray-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Giriş Yap veya Kayıt Ol">
                    <span class="lucide text-xl">&#xe89d;</span>
                </a>
            <?php endif; ?>
            <?php // ----- END: Login/Logout ----- ?>


            <button id="mobile-menu-button" class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-teal-700 dark:hover:text-teal-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Mobil Menüyü Aç/Kapat" aria-expanded="false">
                <span class="lucide text-2xl">&#xe8a7;</span>
            </button>
        </div>
    </div>


    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <nav class="flex flex-col space-y-1 px-4 py-3">
            <a href="<?php echo BASE_URL; ?>?page=home" class="block text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium py-2 rounded <?php echo ($page == 'home' ? 'nav-active' : ''); ?>"><span class="lucide mr-2 text-base">&#xe86d;</span>Ana Sayfa</a>

            <?php // Mobil menüdeki diğer linkler... ?>

            <hr class="my-2 border-gray-200 dark:border-gray-700">
            <?php if ($isLoggedIn): ?>
                <a href="<?php echo BASE_URL; ?>?page=profil" class="block text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium py-2 rounded"><span class="lucide mr-2 text-base">&#xe9a9;</span>Profilim</a>
                <a href="<?php echo BASE_URL; ?>?page=cikis-yap" class="block text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium py-2 rounded"><span class="lucide mr-2 text-base">&#xe89e;</span>Çıkış Yap</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>?page=giris" class="block text-gray-600 hover:text-teal-700 dark:text-gray-300 dark:hover:text-teal-400 font-medium py-2 rounded"><span class="lucide mr-2 text-base">&#xe89d;</span>Giriş Yap / Kayıt Ol</a>
            <?php endif; ?>
        </nav>
    </div>
</header>



<script src="//unpkg.com/alpinejs" defer></script>
