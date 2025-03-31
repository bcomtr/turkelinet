<?php
// app/Views/rehber.php

// Set page title (used in header.php)
// $pageTitle is already set in index.php for this route

// Get listings data passed from index.php
$listings = isset($listings) ? $listings : [];
$error_message = isset($error_message) ? $error_message : null;

?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-8 border-l-4 border-teal-600 dark:border-teal-500 pl-3">
        Türkeli Rehberi
    </h1>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Hata!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <?php // TODO: Add category filters here later ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($listings)): ?>
            <?php foreach ($listings as $listing): ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden group">
                    <?php // Link to the detail page (we'll create this later) ?>
                    <a href="<?php echo BASE_URL . '?page=rehber-detay&id=' . $listing['listing_id']; ?>" class="block">
                        <img src="<?php echo BASE_URL . 'uploads/listings/' . (!empty($listing['featured_image']) ? htmlspecialchars($listing['featured_image']) : 'placeholder_listing.png'); ?>"
                             alt="<?php echo htmlspecialchars($listing['name']); ?>"
                             class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity"
                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/e2e8f0/a0aec0?text=Mekan+Resmi';">
                        <div class="p-4">
                             <span class="text-xs font-semibold text-purple-700 bg-purple-100 dark:text-purple-300 dark:bg-purple-900 px-2 py-1 rounded-full mb-2 inline-block">
                                <?php echo htmlspecialchars(strtoupper($listing['category_name'])); ?>
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-teal-700 dark:group-hover:text-teal-400">
                                <?php echo htmlspecialchars($listing['name']); ?>
                            </h3>
                            <?php if (!empty($listing['address'])): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">
                                    <span class="lucide text-xs mr-1">&#xe8a3;</span> <?php echo htmlspecialchars($listing['address']); ?>
                                </p>
                            <?php endif; ?>
                            <?php // TODO: Add rating display here later ?>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Ekleyen: <?php echo htmlspecialchars($listing['added_by_user'] ?? 'Bilinmiyor'); ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 md:col-span-2 lg:col-span-3 text-center py-8">
                Bu bölümde henüz listelenecek bir yer bulunmamaktadır.
            </p>
        <?php endif; ?>
    </div>

    <?php // TODO: Add pagination controls here later if needed ?>

</main>
