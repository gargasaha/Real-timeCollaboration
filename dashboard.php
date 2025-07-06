<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Real-time Collaboration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            overflow-x: hidden;
            overflow-y: scroll;
            height: 100%;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
        }

        body {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: #f3f3f3;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 850px;
            margin: 48px auto;
            background: linear-gradient(120deg, #ffffff 60%, #f3e7e9 100%);
            border-radius: 22px;
            box-shadow: 0 10px 40px rgba(80, 60, 120, 0.18);
            padding: 48px 36px 36px 36px;
            border: 2px solid #e0c3fc;
            width: 98vw;
        }

        .welcome {
            font-weight: 800;
            color: #6a11cb;
            letter-spacing: 1px;
            font-size: 2rem;
        }

        .room-title {
            color: #2575fc;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-custom {
            min-width: 220px;
            font-size: 1.15rem;
            margin-bottom: 18px;
            font-weight: 600;
            border-radius: 30px;
            box-shadow: 0 4px 16px rgba(106, 17, 203, 0.10);
            transition: transform 0.12s, box-shadow 0.12s;
        }

        .btn-primary.btn-custom {
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            border: none;
        }

        .btn-success.btn-custom {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            color: #222;
        }

        .btn-custom:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 24px rgba(80, 60, 120, 0.18);
        }

        .iframe-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(80, 60, 120, 0.10);
            margin-top: 28px;
            border: 2px solid #a18cd1;
            background: #f8fafc;
            display: flex;
            flex-direction: row;
            gap: 24px;
            padding: 16px;
        }

        .code-editor-section,
        .compile-section,
        .result-section,
        .chat-section {
            flex: 1 1 0;
            min-width: 0;
        }

        .chat-section {
            max-width: 320px;
            min-width: 220px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(80, 60, 120, 0.08);
            padding: 12px;
            margin-left: auto;
        }

        .btn-outline-danger {
            border-radius: 20px;
            font-weight: 600;
            border-width: 2px;
        }

        @media (max-width: 900px) {
            .dashboard-container {
                max-width: 98vw;
                padding: 32px 12px 24px 12px;
            }

            .iframe-container {
                flex-direction: column;
                gap: 16px;
                padding: 8px;
            }

            .chat-section {
                max-width: 100%;
                min-width: 0;
                margin-left: 0;
            }

            .iframe-container iframe {
                height: 50vh;
            }
        }

        @media (max-width: 600px) {
            .dashboard-container {
                padding: 16px 2vw;
                margin: 16px auto;
            }

            .welcome {
                font-size: 1.2rem;
            }

            .room-title {
                font-size: 1.1rem;
            }

            .btn-custom {
                min-width: 120px;
                font-size: 1rem;
                padding: 8px 12px;
            }

            .iframe-container iframe {
                height: 38vh;
            }
        }

        @media (max-width: 400px) {
            .dashboard-container {
                padding: 8px 1vw;
            }

            .btn-custom {
                min-width: 90px;
                font-size: 0.95rem;
            }

            .iframe-container iframe {
                height: 30vh;
            }
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
        <div class="container-fluid">
            <a class="navbar-brand" href="default.php">devCollab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Real-timeCollaboration/login.php">Sign <Inp></Inp></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Real-timeCollaboration/register.php">Sign Up</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="welcome mb-0 text-end d-inline-block align-middle"
                            style="min-width:200px; line-height: 1.8;">
                            Welcome, <span
                                class="fw-bold text-primary"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!
                        </span>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2 align-middle my-2">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="dashboard-container shadow-lg" style="max-width:98vw; width:98vw;">

        <?php
        $sql = "select * from room_member where user_id='{$_SESSION['id']}'";
        $mysqli = mysqli_connect("localhost", "root", "9932", "devcollab");
        if (!$mysqli) {
            die('<div class="alert alert-danger">Connection failed: ' . mysqli_connect_error() . '</div>');
        }
        $result = mysqli_query($mysqli, $sql);
        if (!$result) {
            die('<div class="alert alert-danger">Query failed: ' . mysqli_error($mysqli) . '</div>');
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($mysqli);
        if (!isset($row['room_id'])) {
            ?>

            <div class="text-center mt-5">
                <a href="create_room.php" class="btn btn-primary btn-lg btn-custom me-3 shadow">Create a New Room</a>
                <a href="join_room.php" class="btn btn-success btn-lg btn-custom shadow">Join an Existing Room</a>
            </div>
            <?php
        } else {
            $_SESSION["roomId"] = $row['room_id'];
            $mysqli = mysqli_connect("localhost", "root", "9932", "devcollab");
            if (!$mysqli) {
                die('<div class="alert alert-danger">Connection failed: ' . mysqli_connect_error() . '</div>');
            }
            $sql = "SELECT room_name FROM room WHERE id='" . mysqli_real_escape_string($mysqli, $_SESSION['roomId']) . "'";
            $result = mysqli_query($mysqli, $sql);
            if (!$result) {
                die('<div class="alert alert-danger">Query failed: ' . mysqli_error($mysqli) . '</div>');
            }
            $row = mysqli_fetch_assoc($result);
            echo '<div class="d-flex align-items-center justify-content-between mb-4">';
            echo '<h3 class="room-title mb-0">Room: ' . htmlspecialchars($row['room_name']) . '</h3>';
            echo '<a href="leave_room.php" class="btn btn-outline-danger btn-custom ms-3">Leave Room</a>';
            echo '</div>';
            mysqli_free_result($result);
            mysqli_close($mysqli);
            ?>
            <div class="iframe-container position-relative" style="overflow:hidden;">
                <iframe src="index.php"
                    style="width:100vw; max-width:100%; height:70vh; border:none; background: #f8fafc; overflow:auto;"
                    allowfullscreen loading="lazy" title="Collaboration Room" id="collab-iframe" scrolling="yes"></iframe>
                <button type="button" class="btn btn-light position-absolute top-0 end-0 m-2 shadow-sm"
                    onclick="document.getElementById('collab-iframe').requestFullscreen();" title="Fullscreen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-arrows-fullscreen" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1 1v5h1V2.707l4.146 4.147.708-.708L2.707 2H6V1H1zm14 0h-5v1h3.293l-4.147 4.146.708.708L13.293 2H10V1h5zm-1 14v-5h-1v3.293l-4.146-4.147-.708.708L13.293 14H10v1h5zm-14 0h5v-1H2.707l4.147-4.146-.708-.708L2 13.293V10H1v5z" />
                    </svg>
                </button>
            </div>
            <?php
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
