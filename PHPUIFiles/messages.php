<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');


// Fetch user ID from session
$users_username = $_SESSION['username'];
$usersQuery = $db->prepare("SELECT * FROM users WHERE username = :username");
$usersQuery->bindValue(':username', $users_username, SQLITE3_TEXT);
$usersResult = $usersQuery->execute();
$user = $usersResult->fetchArray(SQLITE3_ASSOC);
$user_id = $user['users_id'];


// Fetch freelancer details
if (isset($_GET['freelancer_id'])) {
    $freelancer_id = $_GET['freelancer_id'];
    $freelancerQuery = $db->prepare("SELECT * FROM freelancers WHERE id = :id");
    $freelancerQuery->bindValue(':id', $freelancer_id, SQLITE3_INTEGER);
    $freelancerResult = $freelancerQuery->execute();
    $freelancer = $freelancerResult->fetchArray(SQLITE3_ASSOC);
} else {
    header("Location: user-dashboard.php");
    exit;
}

// Fetch recent chats
// Update the recent chats query to include last message and timestamp
$recentChatsQuery = $db->prepare("
    SELECT 
        f.id, 
        f.name, 
        f.profile_picture,
        (SELECT message FROM messages 
         WHERE (sender_id = :user_id AND receiver_id = f.id) 
         OR (sender_id = f.id AND receiver_id = :user_id)
         ORDER BY timestamp DESC LIMIT 1) AS last_message,
        (SELECT timestamp FROM messages 
         WHERE (sender_id = :user_id AND receiver_id = f.id) 
         OR (sender_id = f.id AND receiver_id = :user_id)
         ORDER BY timestamp DESC LIMIT 1) AS last_message_time
    FROM freelancers f
    JOIN messages m ON f.id = m.sender_id OR f.id = m.receiver_id
    WHERE m.sender_id = :user_id OR m.receiver_id = :user_id
    GROUP BY f.id
");
$recentChatsQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$recentChatsResult = $recentChatsQuery->execute();


// Helper function to truncate long messages
function truncate($string, $length) {
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}

// Helper function to format message time
function format_message_time($timestamp) {
    if (empty($timestamp)) return '';
    
    $now = new DateTime();
    $messageTime = new DateTime($timestamp);
    $diff = $now->diff($messageTime);
    
    if ($diff->days == 0) {
        // Today - show time only
        return $messageTime->format('h:i A');
    } elseif ($diff->days == 1) {
        // Yesterday
        return 'Yesterday';
    } elseif ($diff->days < 7) {
        // Within a week - show day name
        return $messageTime->format('D');
    } else {
        // Older than a week - show date
        return $messageTime->format('M j');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS files/messages.css">
    <style>
        .message small {
    display: block;
    text-align: right;
    font-size: 0.75rem;
    color: #666;
    margin-top: 4px;
}

.user-message small {
    color: rgba(255, 255, 255, 0.7);
}

.chat-details small {
    color: #666;
    font-size: 0.75rem;
    margin-top: 2px;
}

.last-message {
    color: #666;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
    </style>
</head>
<body>
    <div class="chat-app">
        <!-- Left Side: Recent Chats -->
        <div class="recent-chats">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search freelancers...">
            </div>
            <div class="recent-chats-list" id="recent-chats-list">
            <?php while ($chat = $recentChatsResult->fetchArray(SQLITE3_ASSOC)): ?>
                <div class="chat-item" onclick="location.href='messages.php?freelancer_id=<?= $chat['id'] ?>'">
                    <img src="<?= htmlspecialchars($chat['profile_picture']) ?>" alt="<?= htmlspecialchars($chat['name']) ?>">
                    <div class="chat-details">
                        <h5><?= htmlspecialchars($chat['name']) ?></h5>
                        <p class="last-message"><?= htmlspecialchars(truncate($chat['last_message'] ?? '', 20)) ?></p>
                        <small><?= format_message_time($chat['last_message_time'] ?? '') ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
        </div>

        <!-- Right Side: Chat Interface -->
        <div class="chat-container">
            <div class="chat-header">
                <a href="user_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
                <img src="<?= htmlspecialchars($freelancer['profile_picture']) ?>" alt="Freelancer" class="profile-pic">
                <div class="freelancer-details">
                    <h5><?= htmlspecialchars($freelancer['name']) ?></h5>
                    <p class="status">Online</p>
                </div>
            </div>

            <div class="chat-messages" id="chat-messages">
                <?php
                

                $messagesQuery = $db->prepare("
                    SELECT * FROM messages 
                    WHERE (sender_id = :user_id AND receiver_id = :freelancer_id)
                    OR (sender_id = :freelancer_id AND receiver_id = :user_id)
                    ORDER BY timestamp ASC
                ");
                $messagesQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                $messagesQuery->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                $messagesResult = $messagesQuery->execute();
                
                while ($message = $messagesResult->fetchArray(SQLITE3_ASSOC)) {
                    $messageClass = ($message['sender_id'] == $user_id) ? 'user-message' : 'freelancer-message';
                    // echo $messageClass;
                    echo "<div class='message $messageClass'>
                            <p>{$message['message']}</p>
                            <small>" . date('h:i A', strtotime($message['timestamp'])) . "</small>
                          </div>";
                }
                ?>
            </div>

            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Type a message...">
                <label for="file-input" class="attachment-btn"><i class="fas fa-paperclip"></i></label>
                <input type="file" id="file-input" style="display: none;">
                <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatMessages = document.getElementById('chat-messages');
            const messageInput = document.getElementById('message-input');
            const sendBtn = document.getElementById('send-btn');
            const fileInput = document.getElementById('file-input');
            const searchInput = document.getElementById('search-input');
            const recentChatsList = document.getElementById('recent-chats-list');

            // Scroll to the bottom of the chat
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Send message
            sendBtn.addEventListener('click', function () {
                const message = messageInput.value.trim();
                if (message) {
                    sendMessage(message);
                    messageInput.value = '';
                }
            });

            // Send message on Enter key
            messageInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const message = messageInput.value.trim();
                    if (message) {
                        sendMessage(message);
                        messageInput.value = '';
                    }
                }
            });

            // Send attachment
            fileInput.addEventListener('change', function () {
                const file = fileInput.files[0];
                if (file) {
                    sendAttachment(file);
                }
            });

            // Search freelancers
            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value.toLowerCase();
                const chatItems = recentChatsList.querySelectorAll('.chat-item');
                chatItems.forEach(item => {
                    const name = item.querySelector('h5').textContent.toLowerCase();
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Function to send a message
            function sendMessage(message) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_message.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        loadMessages();
                    }
                };
                xhr.send(`sender_id=<?= $user_id ?>&receiver_id=<?= $freelancer_id ?>&message=${message}`);
            }

            // Function to send an attachment
            function sendAttachment(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('sender_id', <?= $user_id ?>);
                formData.append('receiver_id', <?= $freelancer_id ?>);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_attachment.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        loadMessages();
                    }
                };
                xhr.send(formData);
            }

            // Function to load messages
            function loadMessages() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_messages.php?freelancer_id=<?= $freelancer_id ?>', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        chatMessages.innerHTML = xhr.responseText;
                        // chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
                    }
                };
                xhr.send();
            }

            // Fetch messages every 2 seconds
            setInterval(loadMessages, 2000);
        });
    </script>
</body>
</html>