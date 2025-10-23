<?php

include 'functions.php';

if (isset($_SESSION['user'])) {

    getUserDetails($_SESSION['user']);

} else if (isset($_SESSION['username'])) {
    
    getUserDetails($_SESSION['username']);

} else if (isset($_SESSION['useremail'])) {

    getUserDetails($_SESSION['useremail']);

}

?>