<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PL Number Deduplication</title>
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
<body class="gradient-bg min-h-screen font-sans" x-data="{ darkMode: false, searchQuery: '' }" x-init="() => {
    if (localStorage.getItem('darkMode') === 'true') darkMode = true;
}">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8 animate-slide-in">
    <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">
        <i class="fas fa-list-ul mr-2"></i>PL Number Deduplication
    </h1>
    <div class="flex items-center gap-4">
        <span class="text-white font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
            <i x-show="!darkMode" class="fas fa-moon"></i>
            <i x-show="darkMode" class="fas fa-sun"></i>
        </button>
        <a href="logout.php" class="p-2 rounded-full bg-red-500 text-white hover:bg-red-600">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>

        <!-- Input Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-effect p-8 rounded-2xl shadow-xl animate-slide-in transition-all duration-300 hover:shadow-2xl"
                 x-bind:class="{ 'dark:bg-gray-800': darkMode }">
                <h2 class="text-2xl font-semibold text-white mb-6">Add New PL Number</h2>
                <form action="process.php" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-white">PL Number</label>
                        <div class="relative mt-1">
                            <input type="text" name="pl_number" required
                                   class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                                   placeholder="Enter PL Number">
                            <i class="fas fa-barcode absolute right-3 top-3 text-gray-300"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white">Description</label>
                        <textarea name="description"
                                  class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                                  placeholder="Enter description (optional)" rows="4"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>Add PL Number
                    </button>
                </form>
            </div>

            <!-- Search and Stats -->
            <div class="glass-effect p-8 rounded-2xl shadow-xl animate-slide-in transition-all duration-300 hover:shadow-2xl"
                 x-bind:class="{ 'dark:bg-gray-800': darkMode }">
                <h2 class="text-2xl font-semibold text-white mb-6">Search & Stats</h2>
                <div class="relative mb-6">
                    <input type="text" x-model="searchQuery"
                           class="w-full rounded-lg border-0 bg-white/20 text-white placeholder-gray-300 p-3 focus:ring-2 focus:ring-blue-400"
                           placeholder="Search PL Numbers...">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-300"></i>
                </div>
                <div class="grid grid-cols-2 gap-4 text-white">
                    <div class="bg-blue-500/30 p-4 rounded-lg">
                        <p class="text-lg font-semibold">Total Entries</p>
                        <p class="text-2xl"><?php
                            $conn = new mysqli('localhost', 'root', '', 'pl_deduplication');
                            if ($conn->connect_error) {
                                echo '0';
                            } else {
                                $result = $conn->query("SELECT COUNT(*) as total FROM pl_numbers");
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                $conn->close();
                            }
                        ?></p>
                    </div>
                    <div class="bg-green-500/30 p-4 rounded-lg">
                        <p class="text-lg font-semibold">Unique PLs</p>
                        <p class="text-xl truncate" title="<?php
                            $conn = new mysqli('localhost', 'root', '', 'pl_deduplication');
                            if ($conn->connect_error) {
                                echo 'None';
                            } else {
                                $result = $conn->query("SELECT DISTINCT pl_number FROM pl_numbers");
                                $unique_pls = [];
                                while ($row = $result->fetch_assoc()) {
                                    $unique_pls[] = htmlspecialchars($row['pl_number']);
                                }
                                echo implode(', ', $unique_pls);
                                $conn->close();
                            }
                        ?>"><?php
                            $conn = new mysqli('localhost', 'root', '', 'pl_deduplication');
                            if ($conn->connect_error) {
                                echo 'None';
                            } else {
                                $result = $conn->query("SELECT COUNT(DISTINCT pl_number) as unique_count FROM pl_numbers");
                                if ($row = $result->fetch_assoc()) {
                                    echo $row['unique_count'];
                                } else {
                                    echo '0';
                                }
                                $conn->close();
                            }
                        ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="glass-effect p-8 rounded-2xl shadow-xl animate-slide-in overflow-x-auto"
             x-bind:class="{ 'dark:bg-gray-800': darkMode }">
            <h2 class="text-2xl font-semibold text-white mb-6">PL Numbers List</h2>
            <div class="min-w-full">
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                include 'display.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition-colors">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                const searchQuery = Alpine.reactive({ value: '' });
                Alpine.bind('searchQuery', {
                    ['@input.debounce.300ms']() {
                        window.location.search = `search=${encodeURIComponent(this.searchQuery)}`;
                    }
                });
            });
        });
    </script>
</body>
</html>
