<?php
// app/Views/etkinlikler.php

// $pageTitle is set in index.php

// Get events data passed from index.php
$events = isset($events) ? $events : [];
$error_message = isset($error_message) ? $error_message : null;

// Helper function for date formatting (consider moving to a helpers file)
function format_event_date($datetime) {
    if(!$datetime) return ['month'=>'???', 'day'=>'??'];
    try {
        $date = new DateTime($datetime);
        $months = ['OCAK', 'ŞUBAT', 'MART', 'NİSAN', 'MAYIS', 'HAZİRAN', 'TEMMUZ', 'AĞUSTOS', 'EYLÜL', 'EKİM', 'KASIM', 'ARALIK'];
        return [
            'month' => $months[$date->format('n') - 1],
            'day' => $date->format('d')
        ];
    } catch (Exception $e) {
        return ['month'=>'HATA', 'day'=>'XX'];
    }
}
function format_event_time($datetime) {
    if(!$datetime) return '';
    try {
        $date = new DateTime($datetime);
        return $date->format('H:i');
    } catch (Exception $e) {
        return '';
    }
}
?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-8 border-l-4 border-teal-600 dark:border-teal-500 pl-3">
        Etkinlik Takvimi
    </h1>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Hata!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event):
                $eventDate = format_event_date($event['start_time']);
                $eventTime = format_event_time($event['start_time']);
                // Generate a simple slug for the URL (replace with actual slug later if needed)
                $eventSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $event['title']), '-'));
                ?>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    <div class="flex items-center space-x-4 w-full sm:w-auto">

                        <div class="bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300 p-3 rounded-lg text-center w-16 flex-shrink-0" aria-label="Etkinlik Tarihi: <?php echo htmlspecialchars($eventDate['day'] . ' ' . $eventDate['month']); ?>">
                            <div class="text-xs font-bold uppercase"><?php echo htmlspecialchars($eventDate['month']); ?></div>
                            <div class="text-2xl font-bold"><?php echo htmlspecialchars($eventDate['day']); ?></div>
                        </div>

                        <div class="flex-grow">
                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                                <?php // Link to detail page (to be created later) ?>
                                <a href="<?php echo BASE_URL . '?page=etkinlik&slug=' . htmlspecialchars($eventSlug); ?>" class="hover:text-teal-700 dark:hover:text-teal-400">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex flex-wrap gap-x-3">
                                <?php if (!empty($event['location_name'])): ?>
                                    <span class="inline-flex items-center"><span class="lucide text-sm mr-1">&#xe8a3;</span><?php echo htmlspecialchars($event['location_name']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($eventTime)): ?>
                                    <span class="inline-flex items-center"><span class="lucide text-sm mr-1">&#xe7f4;</span><?php echo htmlspecialchars($eventTime); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <button class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500 text-xs font-semibold py-1.5 px-3 rounded-lg w-full sm:w-auto flex items-center justify-center flex-shrink-0">
                        <span class="lucide text-sm mr-1">&#xe7e7;</span> Detaylar / Katıl (Simülasyon)
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                Yaklaşan etkinlik bulunmamaktadır.
            </p>
        <?php endif; ?>
    </div>

    <?php // TODO: Add pagination or filter by past events later ?>

</main>
