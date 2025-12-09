<?php

include 'functions.php';

function userLogout() {
    unset($_SESSION['user']);
    unset($_SESSION['username']);
    unset($_SESSION['useremail']);
    return true;
}

userLogout();

if (userLogout()) {
    echo 'true';
}

?>