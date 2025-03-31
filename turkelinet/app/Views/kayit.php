<?php
// app/Views/kayit.php
// $pageTitle is set in index.php

// Display potential registration errors passed from the controller (index.php) later
$errors = isset($errors) ? $errors : [];
$old_input = isset($old_input) ? $old_input : [];

?>

<main class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
            Yeni Hesap Oluştur
        </h1>

        <?php // Display general errors here if any ?>
        <?php if (isset($errors['general'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?page=kayit-ol" method="POST" class="space-y-5">
            <?php // CSRF Token should be added here in a real application ?>

            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ad Soyad</label>
                <input type="text" id="full_name" name="full_name" required maxlength="100"
                       value="<?php echo htmlspecialchars($old_input['full_name'] ?? ''); ?>"
                       class="w-full p-3 border <?php echo isset($errors['full_name']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Adınız ve Soyadınız">
                <?php if (isset($errors['full_name'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['full_name']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" required maxlength="50" pattern="[a-zA-Z0-9_]+"
                       value="<?php echo htmlspecialchars($old_input['username'] ?? ''); ?>"
                       class="w-full p-3 border <?php echo isset($errors['username']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Sadece harf, rakam ve _ kullanın">
                <?php if (isset($errors['username'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['username']); ?></p>
                <?php endif; ?>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Giriş yaparken ve profilinizde görünecek.</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta Adresi</label>
                <input type="email" id="email" name="email" required maxlength="100"
                       value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>"
                       class="w-full p-3 border <?php echo isset($errors['email']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="ornek@eposta.com">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['email']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şifre</label>
                <input type="password" id="password" name="password" required minlength="6"
                       class="w-full p-3 border <?php echo isset($errors['password']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="En az 6 karakter">
                <?php if (isset($errors['password'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['password']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="password_confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şifre Tekrar</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6"
                       class="w-full p-3 border <?php echo isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Şifrenizi tekrar girin">
                <?php if (isset($errors['password_confirm'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['password_confirm']); ?></p>
                <?php endif; ?>
            </div>


            <div class="pt-3">
                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg text-base flex items-center justify-center transition duration-200 ease-in-out">
                    <span class="lucide mr-2 text-base">&#xe9cb;</span> Hesap Oluştur
                </button>
            </div>

            <div class="text-center text-sm mt-6">
                <span class="text-gray-600 dark:text-gray-400">Zaten bir hesabınız var mı?</span>
                <a href="<?php echo BASE_URL; ?>?page=giris" class="font-medium text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 hover:underline ml-1">Giriş Yap</a>
            </div>

        </form>
    </div>
</main>
