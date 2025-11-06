<?php

include 'functions.php';

function adminLogout() {
    unset($_SESSION['admin']);
    return true;
}

adminLogout();

if (adminLogout()) {
    echo 'true';
}

?>