<?php
// Set the directory to search
$directory = './';

// Get the list of files in the root directory
$files = array_diff(scandir($directory), array('.', '..'));

// Display the list of files with links
echo "<h2>Files in the root directory:</h2>";
echo "<ul>";
foreach ($files as $file) {
    if (is_file($directory . $file)) {
        echo "<li><a href='{$file}'>{$file}</a></li>";
    }
}
echo "</ul>";
?>