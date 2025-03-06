<?php
require 'config.php';
require_once 'auth_check.php';

// Custom filter that only blocks angle brackets
function basic_filter($str) {
    return str_replace(['<', '>'], ['&lt;', '&gt;'], $str);
}

function highlight_mentions($text) {
    return preg_replace('/@([a-z\.]+)/i', '<span class="mention">@$1</span>', $text);
}

if (!isAuthenticated()) {
    header('Location: login.php');
    exit;
}

session_start();
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $username = basic_filter($_SESSION['username'] ?? $_POST['username']);
    $message = basic_filter($_POST['message']);
    $timestamp = date('Y-m-d H:i:s');
    
    // In a real app, use a database instead of file storage
    $chatEntry = json_encode([
        'timestamp' => $timestamp,
        'username' => $username,
        'message' => $message
    ]) . "\n";
    
    file_put_contents('chat_logs.json', $chatEntry, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Chat - Spanning Tree Corporation</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/chat.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body class="backend">
    <header class="nav-header">
        <div class="nav-container">
            <h1>Employee Chat | Authenticated as <?php echo basic_filter($_SESSION['username'] ?? ''); ?></h1>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="employee_chat.php" class="active">Chat</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="chat-wrapper">
        <div class="chat-sidebar">
            <h3>Online Users</h3>
            <div class="online-users">
                <ul class="user-list">
                    <?php
                    // Default offline users list
                    $offlineUsers = [
                        'k.williams',
                        'r.fujisaki',
                        'h.allen',
                        'cody',
                        'gerard'
                    ];
                    
                    // Always show current user and localadmin
                    $currentUser = $_SESSION['username'] ?? '';
                    echo '<li class="user-item">';
                    echo '<span class="user-status status-online"></span>';
                    echo basic_filter($currentUser ?: 'You');
                    echo '</li>';
                    
                    echo '<li class="user-item">';
                    echo '<span class="user-status status-away"></span>';
                    echo 'localadmin';
                    echo '</li>';
                    
                    // Show offline users except current user
                    foreach ($offlineUsers as $user) {
                        if ($user !== $currentUser) {
                            echo '<li class="user-item">';
                            echo '<span class="user-status status-offline"></span>';
                            echo $user;
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-header">
                <h2>Team Chat</h2>
                <span class="chat-status">Connected</span>
            </div>

            <div class="chat-messages">
                <?php
                if (file_exists('chat_logs.json')) {
                    $messages = array_filter(array_map('trim', file('chat_logs.json')));
                    $messages = array_slice($messages, -50);
                    foreach ($messages as $message) {
                        $data = json_decode($message, true);
                        if ($data) {
                            $isOutgoing = isset($_SESSION['username']) && 
                                        $_SESSION['username'] === $data['username'];
                            echo '<div class="message ' . ($isOutgoing ? 'outgoing' : 'incoming') . '">';
                            echo '<div class="message-info">';
                            echo '<span class="username">' . $data['username'] . '</span>';
                            echo '<span class="message-time">' . $data['timestamp'] . '</span>';
                            echo '</div>';
                            echo '<div class="message-bubble">';
                            echo highlight_mentions($data['message']);
                            echo '</div></div>';
                        }
                    }
                }
                ?>
            </div>

            <div class="chat-input">
                <form method="POST" class="chat-form" id="chatForm">
                    <?php if (!isset($_SESSION['username'])): ?>
                        <input type="text" name="username" placeholder="Your Name" required>
                    <?php endif; ?>
                    <textarea name="message" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom on load
        window.onload = function() {
            const messages = document.querySelector('.chat-messages');
            messages.scrollTop = messages.scrollHeight;
        };

        // Auto-scroll on new message
        document.getElementById('chatForm').onsubmit = function() {
            setTimeout(() => {
                const messages = document.querySelector('.chat-messages');
                messages.scrollTop = messages.scrollHeight;
            }, 100);
        };
    </script>
</body>
</html>
