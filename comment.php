<?php

include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['post_comment'])) {
    $post_id = $_POST['post_id'];
    $comment_content = $_POST['comment'];
    $commenter = $_POST['commenter'];
    
    $conn = connect();

    $sql =
        "INSERT INTO tbcomments
        (`postId`,`commentContent`,`userId`)
        VALUES
        (?,?,?)";
    $comment = $conn->prepare($sql);
    $comment->bind_param('sss',$post_id,$comment_content,$commenter);

    if ($comment->execute()) {
        getComments();
        exit;
    }
}

getComments();

?>