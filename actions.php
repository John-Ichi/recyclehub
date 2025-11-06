<?php

include 'functions.php';

function errorHandler($error_msg) {
    setcookie('post_deletion_error',$error_msg);
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $purpose_of_removal = $_POST['deletion_purpose'];

    $delete_comments =
        "DELETE FROM tbcomments
        WHERE postId='$post_id'";

    if (!$conn->query($delete_comments)) {
        $error_msg = "Error deleting comments.";
        errorHandler($error_msg);
        exit();
    }

    $select_images =
        "SELECT *
        FROM tbimages
        WHERE postId='$post_id'";
    $query_select_images = $conn->query($select_images);

    if (!$query_select_images) {
        $error_msg = "Error fetching images.";
        errorHandler($error_msg);
        exit();
    }

    while ($images = $query_select_images->fetch_assoc()) {
        $image = $images['image'];

        if (file_exists($image)) {
            if (unlink($image)) {
                $delete_images =
                    "DELETE FROM tbimages
                    WHERE postId='$post_id'";
                
                if (!$conn->query($delete_images)) {
                    $error_msg = "Error deleting post images.";
                }

                $select_post =
                    "SELECT *
                    FROM tbposts
                    WHERE postId='$post_id'";
                $query_select_post = $conn->query($select_post);

                if (!$query_select_post) {
                    $error_msg = "Error fetching post.";
                    errorHandler($error_msg);
                    exit();
                }

                $post_details = $query_select_post->fetch_assoc();
                $post_content = $post_details['content'];

                $insert_deletion_log =
                    "INSERT INTO tbpostsdeletionlog
                    (`userId`,`postId`,`content`,`purposeOfDeletion`)
                    VALUES
                    (?,?,?,?)";
                $query_insert_log = $conn->prepare($insert_deletion_log);
                $query_insert_log->bind_param('ssss',$user_id,$post_id,$post_content,$purpose_of_removal);

                if ($query_insert_log->execute()) {
                    $delete_post =
                        "DELETE FROM tbposts
                        WHERE postId='$post_id'";

                    if ($conn->query($delete_post)) {
                        setcookie('delete_successful', 'Post deleted successfully.');
                        header('Location: user_actions.php?user=' . $user_id . '');
                        exit();
                    } else {
                        $error_msg = "Error deleting post.";
                        errorHandler($error_msg);
                        exit();
                    }
                } else {
                    $error_msg = "Error logging deletion record.";
                    errorHandler($error_msg);
                    exit();
                }
            } else {
                $error_msg = "Error deleting post images.";
                errorHandler($error_msg);
                exit();
            }
        } else {
            $error_msg = "Missing images.";
            errorHandler($error_msg);
            exit;
        }
    }
}

?>