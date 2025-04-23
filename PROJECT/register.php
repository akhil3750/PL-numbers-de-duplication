<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PL Number Deduplication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #6EE7B7 0%, #3B82F6 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen font-sans" x-data="{ darkMode: false }" x-init="() => {
    if (localStorage.getItem('darkMode') === 'true') darkMode = true;
}">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8 animate-slide-in">
            <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">
                <i class="fas fa-user-plus mr-2"></i>Register
            </h1>
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                <i x-show="!darkMode" class="fas fa-moon"></i>
                <i x-show="darkMode" class="fas fa-sun"></i>
            </button>
        </header>

        <!-- Register Form -->
        <div class="max-w-md mx-auto glass-effect p-8 rounded-2xl shadow-xl animate-slide-in transition-all duration-300 hover:shadow-2xl"
             x-bind:class="{ 'dark:bg-gray-800': darkMode }">
            <h2 class="text-2xl font-semibold text-white mb-6">Create Account</h2>
            <?php
            session_start();
            if (isset($_SESSION['register_error'])) {
                echo '<p class="text-red-300 mb-4">' . htmlspecialchars($_SESSION['register_error']) . '</p>';
                unset($_SESSION['register_error']);
            }
            if (isset($_SESSION['register_prompt'])) {
                echo '<p class="text-yellow-300 mb-4">' . htmlspecialchars($_SESSION['register_prompt']) . '</p>';
                unset($_SESSION['register_prompt']);
            }
            ?>
            <form action="process_register.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-white">Username</label>
                    <div class="relative mt-1">
                        <input type="text" name="username" required
                               class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter username">
                        <i class="fas fa-user absolute right-3 top-3 text-gray-300"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Email</label>
                    <div class="relative mt-1">
                        <input type="email" name="email" required
                               class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter email"
                               value="<?php echo isset($_SESSION['attempted_email']) ? htmlspecialchars($_SESSION['attempted_email']) : ''; ?>">
                        <i class="fas fa-envelope absolute right-3 top-3 text-gray-300"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Password</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" required
                               class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter password">
                        <i class="fas fa-lock absolute right-3 top-3 text-gray-300"></i>
                    </div>
                </div>
                <button type="submit"
                        class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i>Register
                </button>
            </form>
            <p class="text-white mt-4 text-center">
                Already have an account? <a href="login.php" class="text-blue-300 hover:underline">Login here</a>.
            </p>
        </div>
    </div>
</body>
</html>