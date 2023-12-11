<?php
    $servername = "192.168.100.34";
    $db_username = "root";
    $db_password = "ITBANK";
    $dbname = "vote";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['vote'])) {
    $vote = $_POST['vote'];

    $sql = "INSERT INTO votes (vote_result) VALUES ('$vote')";

    if ($conn->query($sql) === TRUE) {
        echo "Vote successfully recorded.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
