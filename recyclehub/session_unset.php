<?php

include 'functions.php';

session_unset();

if (session_unset()) {
    echo 'true';
}

?>