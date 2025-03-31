<?php
// app/Views/rehber-detay.php

// Check if the $listing variable exists (passed from index.php)
if (!isset($listing) || !$listing) {
    echo '<main class="container mx-auto px-4 py-8 text-center"><p>Mekan bilgisi yüklenirken bir sorun oluştu veya mekan bulunamadı.</p></main>';
    return;
}

// Helper function for date formatting (can be moved to a helpers file later)
function format_turkish_date_listing($datetime) {
    if(!$datetime) return '';
    try {
        $date = new DateTime($datetime);
        $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        return $date->format('d') . ' ' . $months[$date->format('n') - 1] . ' ' . $date->format('Y'); // Only date for listings?
    } catch (Exception $e) {
        return $datetime;
    }
}
?>

<main class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <?php // Display featured image if available ?>
            <?php if (!empty($listing['featured_image'])): ?>
                <img src="<?php echo BASE_URL . 'uploads/listings/' . htmlspecialchars($listing['featured_image']); ?>"
                     alt="<?php echo htmlspecialchars($listing['name']); ?>"
                     class="w-full h-64 md:h-96 object-cover"
                     onerror="this.onerror=null; this.style.display='none';">
            <?php endif; ?>

            <div class="p-6 md:p-8 lg:p-10">


                <div class="mb-4 flex justify-between items-center">
                    <a href="<?php echo BASE_URL . '?page=kategori&slug=' . htmlspecialchars($listing['category_slug']); ?>"
                       class="text-sm font-semibold text-purple-700 bg-purple-100 dark:text-purple-300 dark:bg-purple-900 px-3 py-1 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors">
                        <?php echo htmlspecialchars(strtoupper($listing['category_name'])); ?>
                    </a>
                    <?php // TODO: Add Rating display here ?>
                    <span class="text-sm text-yellow-500">★★★★☆ (Yakında)</span>
                </div>


                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-6 leading-tight">
                    <?php echo htmlspecialchars($listing['name']); ?>
                </h1>


                <?php if(!empty($listing['description'])): ?>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-6">
                        <?php echo nl2br(htmlspecialchars($listing['description'])); // Use nl2br for line breaks, sanitize if needed ?>
                    </div>
                <?php endif; ?>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <?php if(!empty($listing['address'])): ?>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Adres</h4>
                            <p class="text-gray-800 dark:text-gray-200"><?php echo nl2br(htmlspecialchars($listing['address'])); ?></p>
                            <?php // TODO: Add Map link/embed here ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <?php if(!empty($listing['phone'])): ?>
                            <div class="mb-3">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Telefon</h4>
                                <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $listing['phone'])); ?>" class="text-teal-600 dark:text-teal-400 hover:underline">
                                    <?php echo htmlspecialchars($listing['phone']); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($listing['website'])): ?>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Web Sitesi</h4>
                                <?php // Ensure URL has http(s):// prefix ?>
                                <?php $websiteUrl = $listing['website'];
                                if (!preg_match("~^(?:f|ht)tps?://~i", $websiteUrl)) {
                                    $websiteUrl = "http://" . $websiteUrl;
                                }
                                ?>
                                <a href="<?php echo htmlspecialchars($websiteUrl); ?>" target="_blank" rel="noopener noreferrer" class="text-teal-600 dark:text-teal-400 hover:underline break-all">
                                    <?php echo htmlspecialchars($listing['website']); ?> <span class="lucide text-xs ml-1">&#xe85f;</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>



                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Ekleyen: <?php echo htmlspecialchars($listing['added_by_user'] ?? 'Bilinmiyor'); ?> |
                    Eklenme Tarihi: <time datetime="<?php echo date('Y-m-d', strtotime($listing['created_at'])); ?>"><?php echo format_turkish_date_listing($listing['created_at']); ?></time>
                </div>


                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Değerlendirmeler</h3>
                    <div id="reviews">

                        <?php // TODO: Fetch and display reviews/ratings here ?>
                        <p class="text-gray-500 dark:text-gray-400">Değerlendirme bölümü yakında eklenecektir.</p>

                        <?php // TODO: Add review form here ?>
                    </div>
                </div>

            </div>
        </article>
    </div>
</main>
