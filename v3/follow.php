<?php

include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['follow_user'])) { // Follow script
    $conn = connect();

    $followee_id = $_POST['followee_id'];
    $follower_id = $_POST['follower_id'];
    
    // PREVENT SELF-FOLLOWING
    if ($followee_id === $follower_id) {
        echo 'error_self_follow';
        exit;
    }
    
    $check_sql = "SELECT * FROM tbfollows WHERE followee=? AND follower=?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('ss', $followee_id, $follower_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo 'already_following';
        exit;
    }

    $sql =
        "INSERT INTO tbfollows
        (`followee`, `follower`)
        VALUES
        (?,?)";
    $follow = $conn->prepare($sql);
    $follow->bind_param('ss', $followee_id, $follower_id);

    if ($follow->execute()) {
        echo $followee_id;
    } else {
        echo 'error';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user'])) {
    $conn = connect();

    $followee_id = $_POST['followee_id'];
    $follower_id = $_POST['follower_id'];
    
    if ($followee_id === $follower_id) {
        echo 'error_self_unfollow';
        exit;
    }

    $sql = "DELETE FROM tbfollows WHERE followee=? AND follower=?";
    $unfollow = $conn->prepare($sql);
    $unfollow->bind_param('ss', $followee_id, $follower_id);

    if ($unfollow->execute()) {
        echo $followee_id;
    } else {
        echo 'error';
    }
}

?>