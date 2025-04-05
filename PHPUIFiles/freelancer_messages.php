<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Fetch freelancer details from session
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$freelancer_username = $_SESSION["username"];
$freelancerQuery = $db->prepare("SELECT * FROM freelancers WHERE username = :username");
$freelancerQuery->bindValue(':username', $freelancer_username, SQLITE3_TEXT);
$freelancerResult = $freelancerQuery->execute();
$freelancer = $freelancerResult->fetchArray(SQLITE3_ASSOC);
$freelancer_id = $freelancer['id'];

// echo $freelancer_id."<br>";

// Fetch user details if a user is selected
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $userQuery = $db->prepare("SELECT * FROM users WHERE users_id = :users_id");
    $userQuery->bindValue(':users_id', $user_id, SQLITE3_INTEGER);
    $userResult = $userQuery->execute();
    $user = $userResult->fetchArray(SQLITE3_ASSOC);
} else {
    $user = null;
}

// echo $user_id ;
// Update the recent chats query to include last message and timestamp
$recentChatsQuery = $db->prepare("
    SELECT 
        u.users_id AS id, 
        u.users_name AS name, 
        u.users_profile_img AS profile_picture,
        (SELECT message FROM messages 
         WHERE (sender_id = :freelancer_id AND receiver_id = u.users_id) 
         OR (sender_id = u.users_id AND receiver_id = :freelancer_id)
         ORDER BY timestamp DESC LIMIT 1) AS last_message,
        (SELECT timestamp FROM messages 
         WHERE (sender_id = :freelancer_id AND receiver_id = u.users_id) 
         OR (sender_id = u.users_id AND receiver_id = :freelancer_id)
         ORDER BY timestamp DESC LIMIT 1) AS last_message_time
    FROM users u
    JOIN messages m ON u.users_id = m.sender_id OR u.users_id = m.receiver_id
    WHERE m.sender_id = :freelancer_id OR m.receiver_id = :freelancer_id
    GROUP BY u.users_id
");
$recentChatsQuery->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
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
    <title>Freelancer Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS files/messages.css">
</head>
<body>
    <div class="chat-app">
        <!-- Left Side: Recent Chats -->
        <div class="recent-chats">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search users...">
            </div>
            <div class="recent-chats-list" id="recent-chats-list">
            <?php while ($chat = $recentChatsResult->fetchArray(SQLITE3_ASSOC)): ?>
                <div class="chat-item" onclick="location.href='freelancer_messages.php?user_id=<?= $chat['id'] ?>'">
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
            <?php if ($user): ?>
                <div class="chat-header">
                    <a href="freelancer_messages.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
                    <img src="<?= htmlspecialchars($user['users_profile_img']) ?>" alt="User" class="profile-pic">
                    <div class="user-details">
                        <h5><?= htmlspecialchars($user['users_name']) ?></h5>
                        <p class="status">Online</p>
                    </div>
                </div>

                <div class="chat-messages" id="chat-messages">
                    <?php
                    $messagesQuery = $db->prepare("
                        SELECT * FROM messages 
                        WHERE (sender_id = :freelancer_id AND receiver_id = :user_id)
                        OR (sender_id = :user_id AND receiver_id = :freelancer_id)
                        ORDER BY timestamp ASC
                    ");
                    $messagesQuery->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                    $messagesQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                    $messagesResult = $messagesQuery->execute();

                    
                    while ($message = $messagesResult->fetchArray(SQLITE3_ASSOC)) {
                        // Determine if the message is sent by the freelancer or received from the user
                        $messageClass = ($message['sender_id'] == $freelancer_id) ? 'user-message' : 'freelancer-message';
                    
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
            <?php else: ?>
                <div class="no-chat-selected">
                    <p>Select a user to start chatting.</p>
                </div>
            <?php endif; ?>
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
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

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

            // Search users
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

            function sendMessage(message) {
                 const xhr = new XMLHttpRequest();
                 xhr.open('POST', 'send_message.php', true);
                 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 xhr.onload = function () {
                     if (xhr.status === 200) {
                         console.log(xhr.responseText); // Debugging: Log the response
                         loadMessages();
                     } else {
                         console.error('Error sending message:', xhr.statusText); // Debugging: Log errors
                     }
                 };
                 xhr.onerror = function () {
                     console.error('Request failed'); // Debugging: Log network errors
                 };
                 xhr.send(`sender_id=<?= $freelancer_id ?>&receiver_id=<?= $user_id ?>&message=${message}`);
             }



            // Function to send an attachment
            function sendAttachment(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('sender_id', <?= $freelancer_id ?>);
                formData.append('receiver_id', <?= $user_id ?>);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_attachment.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        loadMessages();
                    }
                };
                xhr.send(formData);
            }

            function loadMessages() {
               const xhr = new XMLHttpRequest();
               xhr.open('GET', `fetch_messages_freelancer.php?freelancer_id=<?= $freelancer_id ?>&user_id=<?= $user_id ?>`, true);
               xhr.onload = function () {
                   if (xhr.status === 200) {
                       chatMessages.innerHTML = xhr.responseText;
                       chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
                   }
               };
               xhr.send();
            

            // Fetch messages every 2 seconds
            if (chatMessages) {
                setInterval(loadMessages, 2000);
            }
        }
        });
    </script>
</body>
</html>