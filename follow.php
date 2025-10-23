<?php

include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['follow_user'])) {
    $conn = connect();

    $followee_id = $_POST['followee_id'];
    $follower_id = $_POST['follower_id'];

    $sql =
        "INSERT INTO tbfollows
        (`followee`, `follower`)
        VALUES
        (?,?)";
    $follow = $conn->prepare($sql);
    $follow->bind_param('ss',$followee_id,$follower_id);

    if ($follow->execute()) {
        echo $followee_id;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user'])) {
    $conn = connect();

    $followee_id = $_POST['followee_id'];
    $follower_id = $_POST['follower_id'];

    $sql =
        "DELETE FROM tbfollows WHERE followee='$followee_id' && follower='$follower_id'";
    $unfollow = $conn->prepare($sql);

    if ($unfollow->execute()) {
        echo $followee_id;
    }
}

?>