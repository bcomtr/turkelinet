<?php
// app/Views/home.php

// Sayfa başlığını ayarlayalım (header.php'de kullanılacak)
$pageTitle = 'Ana Sayfa';

// index.php'den gelen verileri kullanalım
$latestPosts = isset($latestPosts) ? $latestPosts : [];
$error_message = isset($error_message) ? $error_message : null;

// Tarih formatlama fonksiyonu (Helpers klasörüne taşınabilir)
function format_turkish_date($datetime) {
    if(!$datetime) return '';
    try {
        $date = new DateTime($datetime);
        $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        return $date->format('d') . ' ' . $months[$date->format('n') - 1] . ' ' . $date->format('Y');
    } catch (Exception $e) {
        return $datetime; // Hata olursa orijinal tarihi döndür
    }
}

?>

<main class="container mx-auto px-4 py-8">

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Hata!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>


    <section id="haberler-anasayfa" aria-labelledby="haberler-baslik-anasayfa" class="mb-12">
        <h2 id="haberler-baslik-anasayfa" class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-l-4 border-teal-600 dark:border-teal-500 pl-3">Öne Çıkan Haberler</h2>


        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">

            <?php if (!empty($latestPosts)): $featuredPost = $latestPosts[0]; ?>
                <div class="md:col-span-8 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group">
                    <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($featuredPost['slug']); ?>" class="block">
                        <img src="<?php echo BASE_URL . 'uploads/images/' . (!empty($featuredPost['featured_image']) ? htmlspecialchars($featuredPost['featured_image']) : 'placeholder_large.png'); ?>"
                             alt="<?php echo htmlspecialchars($featuredPost['title']); ?>"
                             class="w-full h-64 md:h-80 object-cover group-hover:opacity-90 transition-opacity"
                             onerror="this.onerror=null; this.src='https://placehold.co/800x450/e2e8f0/a0aec0?text=Resim+Yok';">
                        <div class="p-5">
                        <span class="text-xs font-semibold text-indigo-700 bg-indigo-100 dark:text-indigo-300 dark:bg-indigo-900 px-2 py-1 rounded-full mb-2 inline-block">
                            <?php echo htmlspecialchars(strtoupper($featuredPost['category_name'])); ?>
                        </span>
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-teal-700 dark:group-hover:text-teal-400">
                                <?php echo htmlspecialchars($featuredPost['title']); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                <?php echo htmlspecialchars(mb_substr(strip_tags($featuredPost['content']), 0, 150)) . '...'; ?>
                            </p>
                            <time datetime="<?php echo date('Y-m-d', strtotime($featuredPost['created_at'])); ?>" class="text-xs text-gray-500 dark:text-gray-400">
                                <?php echo format_turkish_date($featuredPost['created_at']); ?>
                            </time>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="md:col-span-8 bg-white dark:bg-gray-800 rounded-lg shadow p-5 text-center text-gray-500">
                    Öne çıkan haber bulunamadı.
                </div>
            <?php endif; ?>



            <div class="md:col-span-4 space-y-4">
                <?php if (count($latestPosts) > 1): ?>
                    <?php for ($i = 1; $i < min(4, count($latestPosts)); $i++): $sidePost = $latestPosts[$i]; ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group">
                            <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($sidePost['slug']); ?>" class="flex items-center">
                                <img src="<?php echo BASE_URL . 'uploads/images/' . (!empty($sidePost['featured_image']) ? htmlspecialchars($sidePost['featured_image']) : 'placeholder_small.png'); ?>"
                                     alt="<?php echo htmlspecialchars($sidePost['title']); ?>"
                                     class="w-1/3 h-20 object-cover flex-shrink-0"
                                     onerror="this.onerror=null; this.src='https://placehold.co/150x100/e2e8f0/a0aec0?text=Resim+Yok';">
                                <div class="p-3 flex-grow">
                                <span class="text-xs font-semibold text-teal-700 bg-teal-100 dark:text-teal-300 dark:bg-teal-900 px-2 py-1 rounded-full mb-1 inline-block">
                                     <?php echo htmlspecialchars(strtoupper($sidePost['category_name'])); ?>
                                </span>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-teal-700 dark:group-hover:text-teal-400 leading-tight line-clamp-2">
                                        <?php echo htmlspecialchars($sidePost['title']); ?>
                                    </h4>
                                </div>
                            </a>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>

                <?php if (count($latestPosts) < 4): ?>
                    <?php for ($j = 0; $j < (4 - max(1, count($latestPosts))); $j++): // Düzeltme: max(1, ...) öne çıkanı saymamak için ?>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg h-20 flex items-center justify-center text-gray-400 text-sm">Boş Alan</div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>


        <div id="news-feed-home" class="space-y-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-l-4 border-blue-600 dark:border-blue-500 pl-3">Son Haberler</h3>

            <?php if (!empty($latestPosts)): ?>
                <?php $index = 0; // ----- DÜZELTME: Sayaç başlangıcı ----- ?>
                <?php foreach ($latestPosts as $post): ?>
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col sm:flex-row items-start space-x-0 sm:space-x-5">
                        <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post['slug']); ?>" class="block mb-3 sm:mb-0 flex-shrink-0">
                            <img src="<?php echo BASE_URL . 'uploads/images/' . (!empty($post['featured_image']) ? htmlspecialchars($post['featured_image']) : 'placeholder_medium.png'); ?>"
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="rounded-md w-full sm:w-48 h-auto object-cover"
                                 onerror="this.onerror=null; this.src='https://placehold.co/200x130/e2e8f0/a0aec0?text=Resim+Yok';">
                        </a>
                        <div class="flex-grow">
                            <span class="text-xs font-semibold text-blue-700 bg-blue-100 dark:text-blue-300 dark:bg-blue-900 px-2 py-1 rounded-full mb-2 inline-block">
                                <?php echo htmlspecialchars(strtoupper($post['category_name'])); ?>
                            </span>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 hover:text-teal-700 dark:hover:text-teal-400">
                                <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2 line-clamp-2">
                                <?php echo htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 120)) . '...'; ?>
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <time datetime="<?php echo date('Y-m-d', strtotime($post['created_at'])); ?>">
                                    <?php echo format_turkish_date($post['created_at']); ?>
                                </time>
                                <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post['slug']); ?>#comments" class="flex items-center hover:text-teal-600 dark:hover:text-teal-400">
                                    <span class="lucide text-sm mr-1">&#xe8b9;</span>
                                    <?php echo $post['comment_count']; ?> Yorum
                                </a>
                            </div>
                        </div>
                    </article>


                    <?php // ----- DÜZELTME: $loop->index yerine $index kullanıldı ----- ?>
                    <?php if ($index == 1): // İkinci haberden sonra reklam göster (index 0'dan başlar) ?>
                        <div class="my-6 ad-placeholder h-24 md:h-32">
                            <span>Yatay Reklam Alanı (Örn: 728x90)</span>
                        </div>
                    <?php endif; ?>

                    <?php $index++; // ----- DÜZELTME: Sayacı artır ----- ?>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 text-center text-gray-500">
                    Henüz yayınlanmış haber bulunmamaktadır.
                </div>
            <?php endif; ?>



            <div class="text-center mt-8">
                <a href="<?php echo BASE_URL; ?>?page=haberler" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 inline-block">
                    Tüm Haberler <span class="lucide ml-1 text-sm">&#xe7c1;</span>
                </a>
            </div>

        </div>
    </section>



    <section id="rehber-anasayfa" aria-labelledby="rehber-baslik-anasayfa" class="mb-12">
        <h2 id="rehber-baslik-anasayfa" class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-l-4 border-teal-600 dark:border-teal-500 pl-3">Türkeli Rehberi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group">
                <a href="<?php echo BASE_URL; ?>?page=rehber-detay&slug=lezzet-duragi-balik-restorani">
                    <img src="https://placehold.co/600x400/fda4af/ffffff?text=Restoran+Resmi" alt="Restoran: Lezzet Durağı Balık Restoranı" class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-teal-700 dark:group-hover:text-teal-400">Lezzet Durağı Balık Restoranı</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Taze deniz ürünleri ve eşsiz manzara...</p>
                        <div class="flex items-center text-sm" aria-label="Değerlendirme: 5 üzerinden 4 yıldız">
                            <span class="text-yellow-500 flex items-center">
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base text-gray-300 dark:text-gray-600" aria-hidden="true">&#xe976;</span>
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(25 Değerlendirme)</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group">
                <a href="<?php echo BASE_URL; ?>?page=rehber-detay&slug=tarihi-turkeli-kalesi">
                    <img src="https://placehold.co/600x400/a78bfa/ffffff?text=Gezilecek+Yer" alt="Gezilecek Yer: Tarihi Türkeli Kalesi" class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-teal-700 dark:group-hover:text-teal-400">Tarihi Türkeli Kalesi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">İlçenin tarihine tanıklık eden önemli bir yapı...</p>
                        <div class="flex items-center text-sm" aria-label="Değerlendirme: 5 üzerinden 5 yıldız">
                            <span class="text-yellow-500 flex items-center">
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                                <span class="lucide text-base mr-0.5" aria-hidden="true">&#xe976;</span>
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(18 Değerlendirme)</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden group relative">
                <a href="<?php echo BASE_URL; ?>?page=harita">
                    <img src="https://placehold.co/600x400/7dd3fc/ffffff?text=Etkile%C5%9Fimli+Harita" alt="Etkileşimli Türkeli Haritası (Yakında)" class="w-full h-48 object-cover opacity-70 group-hover:opacity-90 transition-opacity">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h4 class="text-xl font-semibold text-white text-center p-4">Etkileşimli Türkeli Haritası <br><span class="text-sm font-normal">(Yakında)</span></h4>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Restoranları, otelleri ve gezilecek yerleri haritada keşfedin.</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="<?php echo BASE_URL; ?>?page=rehber" class="text-teal-600 dark:text-teal-400 hover:underline font-medium">Tüm Rehber Öğelerini Gör <span class="lucide text-sm ml-1">&#xe7c1;</span></a>
        </div>
    </section>


    <section id="etkinlikler-anasayfa" aria-labelledby="etkinlikler-baslik-anasayfa" class="mb-12">
        <h2 id="etkinlikler-baslik-anasayfa" class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-l-4 border-teal-600 dark:border-teal-500 pl-3">Yaklaşan Etkinlikler</h2>
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300 p-3 rounded-lg text-center w-16 flex-shrink-0" aria-label="Etkinlik Tarihi: 15 Nisan">
                        <div class="text-xs font-bold uppercase">Nisan</div>
                        <div class="text-2xl font-bold">15</div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100"><a href="<?php echo BASE_URL; ?>?page=etkinlik&slug=turkeli-bahar-senligi" class="hover:text-teal-700 dark:hover:text-teal-400">Türkeli Bahar Şenliği</a></h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><span class="lucide text-sm mr-1">&#xe8a3;</span>Türkeli Meydanı <span class="lucide text-sm ml-3 mr-1">&#xe7f4;</span>14:00</p>
                    </div>
                </div>
                <button class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500 text-xs font-semibold py-1.5 px-3 rounded-lg w-full sm:w-auto flex items-center justify-center">
                    <span class="lucide text-sm mr-1">&#xe7e7;</span> Katılacağım (RSVP Simülasyon)
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 p-3 rounded-lg text-center w-16 flex-shrink-0" aria-label="Etkinlik Tarihi: 22 Nisan">
                        <div class="text-xs font-bold uppercase">Nisan</div>
                        <div class="text-2xl font-bold">22</div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100"><a href="<?php echo BASE_URL; ?>?page=etkinlik&slug=yerel-urunler-pazari" class="hover:text-indigo-700 dark:hover:text-indigo-400">Yerel Ürünler Pazarı</a></h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><span class="lucide text-sm mr-1">&#xe8a3;</span>Kapalı Pazar Yeri <span class="lucide text-sm ml-3 mr-1">&#xe7f4;</span>09:00 - 17:00</p>
                    </div>
                </div>
                <button class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500 text-xs font-semibold py-1.5 px-3 rounded-lg w-full sm:w-auto flex items-center justify-center">
                    <span class="lucide text-sm mr-1">&#xe7e7;</span> Katılacağım (RSVP Simülasyon)
                </button>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="<?php echo BASE_URL; ?>?page=etkinlikler" class="text-teal-600 dark:text-teal-400 hover:underline font-medium">Tüm Etkinlikleri Gör <span class="lucide text-sm ml-1">&#xe7c1;</span></a>
        </div>
    </section>



    <section id="social-features-anasayfa" aria-labelledby="social-baslik-anasayfa" class="mb-12 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
            <h3 id="social-baslik-anasayfa" class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Son Yorumlar & Tartışmalar</h3>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                <div class="space-y-4">
                    <div class="comment">
                        <div class="comment-meta flex justify-between items-center">
                            <span><strong class="text-gray-800 dark:text-gray-100">Ayşe Yılmaz</strong> <span class="text-gray-500 dark:text-gray-400">şunu yorumladı:</span> <a href="<?php echo BASE_URL; ?>?page=haber&slug=okullarda-bilim-senligi-heyecani#comments-1" class="text-teal-600 dark:text-teal-400 hover:underline">Okullarda Bilim Şenliği</a></span>
                            <time datetime="P1D" class="text-xs text-gray-500 dark:text-gray-400">1 gün önce</time>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Çocuklar harika projeler yapmışlar, emeği geçen öğretmenlerimizi tebrik ederim!</p>
                    </div>
                    <div class="comment">
                        <div class="comment-meta flex justify-between items-center">
                            <span><strong class="text-gray-800 dark:text-gray-100">Mehmet Öztürk</strong> <span class="text-gray-500 dark:text-gray-400">şunu yanıtladı:</span> <a href="<?php echo BASE_URL; ?>?page=haber&slug=okullarda-bilim-senligi-heyecani#comment-xyz" class="text-teal-600 dark:text-teal-400 hover:underline">Ayşe Yılmaz</a></span>
                            <time datetime="PT23H" class="text-xs text-gray-500 dark:text-gray-400">23 saat önce</time>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Kesinlikle katılıyorum Ayşe Hanım, gelecek nesillerimiz umut veriyor.</p>
                    </div>
                    <div class="comment">
                        <div class="comment-meta flex justify-between items-center">
                            <span><strong class="text-gray-800 dark:text-gray-100">Zeynep Kaya</strong> <span class="text-gray-500 dark:text-gray-400">şunu yorumladı:</span> <a href="<?php echo BASE_URL; ?>?page=haber&slug=yoresel-yemekler-festivali-duzenlendi#comments-2" class="text-teal-600 dark:text-teal-400 hover:underline">Yöresel Yemekler Festivali</a></span>
                            <time datetime="P2D" class="text-xs text-gray-500 dark:text-gray-400">2 gün önce</time>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Keşke daha fazla okulda böyle etkinlikler yapılsa.</p>
                    </div>
                </div>
                <div class="text-center mt-6">
                    <a href="<?php echo BASE_URL; ?>?page=forum" class="text-sm text-teal-600 dark:text-teal-400 hover:underline font-medium">Forumda Tartışmaya Devam Et</a>
                </div>
            </div>
        </div>


        <div class="lg:col-span-1 space-y-6">

            <div class="ad-placeholder h-64">
                <span>Dikey Reklam Alanı<br>(Örn: 300x250)</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Haber Gönderin!</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Türkeli'de gördüğünüz, duyduğunuz bir haberi veya çektiğiniz ilginç bir fotoğrafı bizimle paylaşın.</p>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm flex items-center justify-center">
                    <span class="lucide mr-1 text-sm">&#xe956;</span> İçerik Gönder (Simülasyon)
                </button>
            </div>


            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Haftanın Anketi</h4>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Türkeli'nin en çok hangi yönünü seviyorsunuz?</p>
                <fieldset class="space-y-2 text-sm">
                    <legend class="sr-only">Anket Seçenekleri</legend>
                    <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="radio" name="poll-home" value="doga" class="form-radio text-teal-600 focus:ring-teal-500">
                        <span>Doğası ve Denizi</span>
                    </label>
                    <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="radio" name="poll-home" value="sakinlik" class="form-radio text-teal-600 focus:ring-teal-500">
                        <span>Sakinliği ve Huzuru</span>
                    </label>
                    <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="radio" name="poll-home" value="insanlar" class="form-radio text-teal-600 focus:ring-teal-500">
                        <span>İnsanları ve Komşuluk</span>
                    </label>
                    <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="radio" name="poll-home" value="yemekler" class="form-radio text-teal-600 focus:ring-teal-500">
                        <span>Yemekleri</span>
                    </label>
                </fieldset>
                <button class="w-full mt-4 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold py-2 px-4 rounded-lg text-sm flex items-center justify-center cursor-not-allowed" disabled>
                    <span class="lucide mr-1 text-sm">&#xe7db;</span> Oy Ver (Simülasyon)
                </button>
            </div>


            <div id="forum-anasayfa" class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 text-center">
                <span class="lucide text-4xl text-gray-400 dark:text-gray-500 mb-3 inline-block">&#xe9a4;</span>
                <h4 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Türkeli Forum</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Sorular sorun, fikirlerinizi paylaşın, toplulukla etkileşime geçin.</p>
                <a href="<?php echo BASE_URL; ?>?page=forum" class="text-teal-600 dark:text-teal-400 hover:underline font-medium text-sm">Foruma Göz At <span class="lucide text-xs ml-0.5">&#xe7c1;</span></a>
            </div>

        </div>
    </section>

</main>
