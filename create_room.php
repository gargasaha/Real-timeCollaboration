<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        $room_name = $_POST['room_name'];
        $password = $_POST['password'];

        $mysqli = mysqli_connect("localhost", "root", "9932", "devcollab");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = "SELECT count(*) as count FROM room WHERE room_name = '" . mysqli_real_escape_string($mysqli, $room_name) . "'";
        $result = mysqli_query($mysqli, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($mysqli));
        }
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        mysqli_free_result($result);

        if (isset($count) && $count > 0) {
            echo "<script>alert('Room already exists');</script>";
        } else {
            $stmt = mysqli_prepare($mysqli, "INSERT INTO room (room_name, room_password) VALUES (?, ?)");
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($mysqli));
            }
            mysqli_stmt_bind_param($stmt, "ss", $room_name, $password);
            if (mysqli_stmt_execute($stmt)) {
                $sql="select id from room where room_name='$room_name' and room_password='$password'";
                $result = mysqli_query($mysqli, $sql);
                if (!$result) {
                    die("Query failed: " . mysqli_error($mysqli));
                }   
                $row = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                $_SESSION['roomId'] = $row['id'];
                mysqli_stmt_close($stmt);
                $sql="insert into room_member (room_id, user_id) values ('{$row['id']}', '{$_SESSION['id']}')";
                if (!mysqli_query($mysqli, $sql)) {
                    echo "<script>alert('Error adding member to room: " . mysqli_error($mysqli) . "');</script>";
                }

                $sql="insert into code_bases (codes,room_id) values ('', {$_SESSION['roomId']})";
                if (!mysqli_query($mysqli, $sql)) {
                    echo "<script>alert('Error creating code base: " . mysqli_error($mysqli) . "');</script>";
                }

                mysqli_close($mysqli);
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Error creating room: " . mysqli_stmt_error($stmt) . "');</script>";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($mysqli);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            background: rgba(255,255,255,0.9);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4">
                    <h2 class="text-center mb-4" style="color:#2575fc;">Create a New Room</h2>
                    <form action="create_room.php" method="post">
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Room Name</label>
                            <input type="text" id="room_name" name="room_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">Create Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>