<?php // app/Views/partials/footer.php ?>


<footer class="bg-gray-800 dark:bg-gray-800 text-gray-300 dark:text-gray-300 border-t border-gray-700 dark:border-gray-700 pt-12 pb-8" aria-labelledby="footer-baslik">
    <div class="container mx-auto px-4">


        <div class="mb-8 ad-placeholder h-20">
            <span>Alt Yatay Reklam Alanı (Örn: 728x90 veya Esnek)</span>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">

            <div>
                <h5 id="footer-baslik" class="text-lg font-semibold text-white mb-3"><?php echo SITE_NAME; ?></h5>
                <p class="text-sm text-gray-400">Türkeli'nin güncel, tarafsız ve sosyal haber platformu.</p>

                <div class="mt-4 flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white" aria-label="Facebook sayfamız"><span class="lucide text-xl">&#xe84f;</span></a>
                    <a href="#" class="text-gray-400 hover:text-white" aria-label="Twitter hesabımız"><span class="lucide text-xl">&#xe98a;</span></a>
                    <a href="#" class="text-gray-400 hover:text-white" aria-label="Instagram hesabımız"><span class="lucide text-xl">&#xe876;</span></a>
                    <a href="#" class="text-gray-400 hover:text-white" aria-label="YouTube kanalımız"><span class="lucide text-xl">&#xe9d1;</span></a>
                </div>
            </div>

            <nav aria-label="Hızlı Bağlantılar">
                <h5 class="text-base font-semibold text-white mb-3">Hızlı Bağlantılar</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo BASE_URL; ?>?page=hakkimizda" class="text-gray-400 hover:text-white hover:underline">Hakkımızda</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=kunye" class="text-gray-400 hover:text-white hover:underline">Künye</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=reklam" class="text-gray-400 hover:text-white hover:underline">Reklam Ver</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=iletisim" class="text-gray-400 hover:text-white hover:underline">İletişim</a></li>
                </ul>
            </nav>

            <nav aria-label="Kategoriler">
                <h5 class="text-base font-semibold text-white mb-3">Kategoriler</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo BASE_URL; ?>?page=kategori&slug=gundem" class="text-gray-400 hover:text-white hover:underline">Gündem</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=kategori&slug=ekonomi" class="text-gray-400 hover:text-white hover:underline">Ekonomi</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=kategori&slug=spor" class="text-gray-400 hover:text-white hover:underline">Spor</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=kategori&slug=saglik" class="text-gray-400 hover:text-white hover:underline">Sağlık</a></li>
                </ul>
            </nav>

            <nav aria-label="Yasal Bilgiler">
                <h5 class="text-base font-semibold text-white mb-3">Yasal</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo BASE_URL; ?>?page=gizlilik-politikasi" class="text-gray-400 hover:text-white hover:underline">Gizlilik Politikası</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=kullanim-kosullari" class="text-gray-400 hover:text-white hover:underline">Kullanım Koşulları</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=cerez-politikasi" class="text-gray-400 hover:text-white hover:underline">Çerez Politikası</a></li>
                </ul>
            </nav>
        </div>
        <div class="border-t border-gray-700 pt-6 text-center text-xs text-gray-500">
            &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tüm hakları saklıdır.
        </div>
    </div>
</footer>



<script>
    // Mobil Menü Scripti
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if(menuButton && mobileMenu) { // Elementlerin varlığını kontrol et
        menuButton.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            menuButton.setAttribute('aria-expanded', !isHidden);
            const icon = menuButton.querySelector('.lucide');
            if(icon) icon.innerHTML = isHidden ? '&#xe8a7;' : '&#xe800;';
        });
    }

    // Dark Mode Toggle Scripti
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if(darkModeToggle) { // Elementin varlığını kontrol et
        const sunIcon = darkModeToggle.querySelector('.sun-icon');
        const moonIcon = darkModeToggle.querySelector('.moon-icon');

        const userPrefersDark = localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

        function applyDarkMode(isDark) {
            document.documentElement.classList.toggle('dark', isDark);
            if(sunIcon) sunIcon.classList.toggle('hidden', isDark);
            if(moonIcon) moonIcon.classList.toggle('hidden', !isDark);
            localStorage.setItem('darkMode', isDark);
            darkModeToggle.setAttribute('aria-pressed', isDark);
        }

        applyDarkMode(userPrefersDark); // Sayfa yüklenirken uygula

        darkModeToggle.addEventListener('click', () => {
            const isDark = !document.documentElement.classList.contains('dark');
            applyDarkMode(isDark);
        });
    }

    // "Daha Fazla Haber Yükle" Simülasyonu (Bu kısım AJAX ile değiştirilecek)
    const loadMoreButton = document.getElementById('load-more-news');
    const moreNewsContainer = document.getElementById('more-news');
    if(loadMoreButton && moreNewsContainer) {
        loadMoreButton.addEventListener('click', () => {
            moreNewsContainer.classList.remove('hidden');
            loadMoreButton.textContent = 'Daha Fazla Haber Yok';
            loadMoreButton.disabled = true;
            loadMoreButton.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }

    // Buraya diğer genel JavaScript kodları eklenebilir
    // Örneğin, AJAX istekleri için fonksiyonlar vb. (ileriki adımlarda)

</script>

</body>
</html>
