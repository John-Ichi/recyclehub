<?php

session_start();
include 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = connect();
    
    $follower_id = $_POST['follower_id'];
    $followee_id = $_POST['followee_id'];
    

    $sql = "SELECT * FROM tbfollows WHERE follower=? AND followee=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $follower_id, $followee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['isFollowing' => true]);
    } else {
        echo json_encode(['isFollowing' => false]);
    }
    
    $stmt->close();
    $conn->close();
}

?>