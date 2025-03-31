<?php
// app/Views/haberler.php

// $pageTitle is set in index.php

// Get posts data passed from index.php
$posts = isset($posts) ? $posts : []; // Use $posts variable name
$error_message = isset($error_message) ? $error_message : null;

// Helper function for date formatting (copied from home.php)
function format_turkish_date($datetime) {
    if(!$datetime) return ''; try { $date = new DateTime($datetime); $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık']; return $date->format('d') . ' ' . $months[$date->format('n') - 1] . ' ' . $date->format('Y'); } catch (Exception $e) { return $datetime; }
}

?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-8 border-l-4 border-teal-600 dark:border-teal-500 pl-3">
        Tüm Haberler
    </h1>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Hata!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <?php // TODO: Add category filters here later ?>

    <div class="space-y-6">
        <?php if (!empty($posts)): ?>
            <?php $adCounter = 0; // Counter for inserting ads ?>
            <?php foreach ($posts as $post_item): // Use $post_item to avoid conflict if $post is used elsewhere ?>
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col sm:flex-row items-start space-x-0 sm:space-x-5">
                    <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post_item['slug']); ?>" class="block mb-3 sm:mb-0 flex-shrink-0">
                        <img src="<?php echo BASE_URL . 'uploads/images/' . (!empty($post_item['featured_image']) ? htmlspecialchars($post_item['featured_image']) : 'placeholder_medium.png'); ?>"
                             alt="<?php echo htmlspecialchars($post_item['title']); ?>"
                             class="rounded-md w-full sm:w-48 h-auto object-cover"
                             onerror="this.onerror=null; this.src='https://placehold.co/200x130/e2e8f0/a0aec0?text=Resim+Yok';">
                    </a>
                    <div class="flex-grow">
                        <span class="text-xs font-semibold text-blue-700 bg-blue-100 dark:text-blue-300 dark:bg-blue-900 px-2 py-1 rounded-full mb-2 inline-block">
                            <?php echo htmlspecialchars(strtoupper($post_item['category_name'])); ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 hover:text-teal-700 dark:hover:text-teal-400">
                            <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post_item['slug']); ?>">
                                <?php echo htmlspecialchars($post_item['title']); ?>
                            </a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2 line-clamp-2">
                            <?php echo htmlspecialchars(mb_substr(strip_tags($post_item['content']), 0, 120)) . '...'; ?>
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <time datetime="<?php echo date('Y-m-d', strtotime($post_item['created_at'])); ?>">
                                <?php echo format_turkish_date($post_item['created_at']); ?>
                            </time>
                            <a href="<?php echo BASE_URL; ?>?page=haber&slug=<?php echo htmlspecialchars($post_item['slug']); ?>#comments" class="flex items-center hover:text-teal-600 dark:hover:text-teal-400">
                                <span class="lucide text-sm mr-1">&#xe8b9;</span>
                                <?php echo $post_item['comment_count']; ?> Yorum
                            </a>
                        </div>
                    </div>
                </article>

                <?php // Insert an ad placeholder every few posts (e.g., after 3rd post) ?>
                <?php $adCounter++; ?>
                <?php if ($adCounter % 3 == 0 && $adCounter < count($posts)): ?>
                    <div class="my-6 ad-placeholder h-24 md:h-32">
                        <span>Yatay Reklam Alanı (Örn: 728x90)</span>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                Henüz yayınlanmış haber bulunmamaktadır.
            </p>
        <?php endif; ?>
    </div>

    <?php // TODO: Add pagination controls here later ?>

</main>
