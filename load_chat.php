<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$conn = mysqli_connect('localhost', 'root', '9932', 'devcollab');
if (mysqli_connect_errno()) {
    die('Connection failed: ' . mysqli_connect_error());
}
$sql = 'SELECT * FROM user_messages WHERE roomId=' . intval($_SESSION['roomId']) . ' ORDER BY tm ASC';
$result = $conn->query($sql);
if ($result) {
    echo '<style>
.chat-message {
    width: 100%;
    margin: 10px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.chat-message.me {
    text-align: center;
}
.chat-bubble {
    width: 100%;
    max-width: 100%;
    padding: 10px 15px;
    border-radius: 18px;
    margin-bottom: 2px;
    font-size: 15px;
    line-height: 1.5;
    box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    position: relative;
    word-break: break-word;
    margin-left: 0;
    margin-right: 0;
    text-align: center;
}
.chat-bubble.me {
    background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.chat-bubble.them {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: #1a237e;
    border-bottom-left-radius: 4px;
}
.chat-meta {
    font-size: 12px;
    color: #607d8b;
    margin-top: 2px;
    text-align: center;
}
</style>';

    while ($row = $result->fetch_assoc()) {
        $isMe = ($row['fromId'] == $_SESSION['id']);
        $isToAll = (intval($row['toId']) === 0);
        $isToMe = (intval($row['toId']) == $_SESSION['id']);

        if ($isMe) {
            if (!$isToAll) {
                $userSql = 'SELECT username FROM users WHERE id=' . intval($row['toId']);
                $userResult = $conn->query($userSql);
                $userRow = $userResult ? $userResult->fetch_assoc() : null;
                $toUsername = $userRow ? htmlspecialchars($userRow['username']) : 'Unknown';
                echo '<div class="chat-message">
                        <div class="chat-bubble me" style="margin-left:auto;">
                            <strong>Me → ' . $toUsername . '</strong><br>' . htmlspecialchars($row['message']) . '
                        </div>
                        <div class="chat-meta" style="text-align: left; margin-left:auto;">' . htmlspecialchars($row['tm']) . '</div>
                    </div>';
            } else {
                echo '<div class="chat-message">
                        <div class="chat-bubble me">
                            <strong>Me → All</strong><br>' . htmlspecialchars($row['message']) . '
                        </div>
                        <div class="chat-meta">' . htmlspecialchars($row['tm']) . '</div>
                    </div>';
            }
        } else {
            $userSql = 'SELECT username FROM users WHERE id=' . intval($row['fromId']);
            $userResult = $conn->query($userSql);
            $userRow = $userResult ? $userResult->fetch_assoc() : null;
            $fromUsername = $userRow ? htmlspecialchars($userRow['username']) : 'Unknown';

            if ($isToAll) {
                echo '<div class="chat-message">
                        <div class="chat-bubble them">
                            <strong>' . $fromUsername . ' → All</strong><br>' . htmlspecialchars($row['message']) . '
                        </div>
                        <div class="chat-meta">' . htmlspecialchars($row['tm']) . '</div>
                    </div>';
            } else if ($isToMe) {
                echo '<div class="chat-message">
                        <div class="chat-bubble them">
                            <strong>' . $fromUsername . ' → Me</strong><br>' . htmlspecialchars($row['message']) . '
                        </div>
                        <div class="chat-meta">' . htmlspecialchars($row['tm']) . '</div>
                    </div>';
            }
        }
    }
} else {
    echo '<div class="error">Error loading messages: ' . htmlspecialchars($conn->error) . '</div>';
}

$conn->close();
