<?php error_reporting(0); session_start();

// Function to sanitize input
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize the inputs
    $message = sanitize_input($_POST['message']);
    $category = sanitize_input($_POST['category']);

    // Get the current date in the format "d/m/Y"
    $current_date = date('d/m/Y');

    // Sanitize the category for the directory name (remove unwanted characters)
    $sanitized_category = preg_replace('/[^a-zA-Z0-9_-]/', '_', $category);

    // Define the directory and file path
    $directory = 'messages/' . $sanitized_category;
    $file_path = $directory . '/' . str_replace('/', '_', $current_date) . '.html';

    // Check if the directory exists, if not, create it
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Prepare the content to save
    $content = "<div class='message'>";
    if (isset($_SESSION['user'])) {
        $user = sanitize_input($_SESSION['user']);
    } else {
        $user = 'user';
    }
    $content .= "<p class='user'>$user</p>";
    $content .= "<p class='message-text'>$message</p></div>";

    // Save the content to the HTML file using fopen in append mode
    $file_handle = fopen($file_path, 'a');
    if ($file_handle) {
        fwrite($file_handle, $content);
        fclose($file_handle);
    }

    // Embed the stored HTML file content
    if (file_exists($file_path)) {
        $stored_content = file_get_contents($file_path);
    } else {
        $stored_content = '<p>Error: Could not retrieve the stored message file.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }
        .form-container, .result-container {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }
        .form-container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, textarea, input[type="text"] {
            margin-bottom: 15px;
        }
        textarea, input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result-container {
            background-color: #f9f9f9;
            padding: 20px;
            overflow-y: auto;
        }
        .message {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .user {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message-text {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Messages</h2>
        <form method="post" action="">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            <input type="submit" value="Submit">
        </form>
    </div>
    <div class="result-container">
        <?php
        // Display the stored HTML file content
        if (isset($stored_content)) {
            echo $stored_content;
        }
        ?>
    </div>
</body>
</html>
