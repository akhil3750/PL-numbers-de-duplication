<?php
require_once 'db_connect.php';

$sql = "SELECT * FROM pl_numbers ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="w-full text-left text-white">';
        echo '<thead><tr>';
        echo '<th class="p-3 bg-blue-500/30 rounded-tl-lg">PL Number</th>';
        echo '<th class="p-3 bg-blue-500/30">Description</th>';
        echo '<th class="p-3 bg-blue-500/30">Status</th>';
        echo '<th class="p-3 bg-blue-500/30 rounded-tr-lg">Added</th>';
        echo '</tr></thead><tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $status = $row['is_duplicate'] ? 
                '<span class="text-red-400 font-medium">Duplicate</span>' : 
                '<span class="text-green-400 font-medium">Unique</span>';
            
            echo '<tr class="border-b border-white/10 hover:bg-white/10 transition-colors">';
            echo '<td class="p-3">' . htmlspecialchars($row['pl_number']) . '</td>';
            echo '<td class="p-3">' . htmlspecialchars($row['description']) . '</td>';
            echo '<td class="p-3">' . $status . '</td>';
            echo '<td class="p-3">' . $row['created_at'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p class="text-white text-center">No PL numbers found.</p>';
    }
    
    mysqli_free_result($result);
} else {
    echo '<p class="text-red-400 text-center">Error: ' . htmlspecialchars(mysqli_error($conn)) . '</p>';
}

mysqli_close($conn);
?>