<?php

include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_notice'])) {
    $user_id = $_POST['user_id'];

    $sql =
        "UPDATE tbpostsdeletionlog
        SET confirmed='1'
        WHERE userId='$user_id'";
    if ($conn->query($sql)) {
        exit;
    }
}

?>