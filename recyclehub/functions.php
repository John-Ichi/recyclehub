<?php
// All functions

function connect() { // Connect to database
    $conn = new mysqli('localhost','root','','recyclehub');
    if ($conn->connect_error) {
        error_log('Connection error: ' . $conn->connect_error);
    } else {
        return $conn;
    }
}

$conn = connect();
session_start();

// Helper functions

function checkDuplicateEmail($email) { // Check duplicate emails
    $conn = connect();
    
    $sql =
        "SELECT userEmail FROM tblogininfo WHERE userEmail='$email'";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) return true;
    else return false;
}

function checkDuplicateUsername($username) { // Check duplicate usernames
    $conn = connect();

    $sql =
        "SELECT username FROM tblogininfo WHERE username='$username'";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) return true;
    else return false;
}

// User registration

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign_up'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    
    // Check for duplicate email and username
    $check_email = checkDuplicateEmail($email);
    $check_username = checkDuplicateUsername($username);
    if ($check_email == true || $check_username == true) {
        setcookie('duplicate_email', $email);
        setcookie('duplicate_username', $username);
        header('Location: register.php');
        return;
    }
    
    // Hash password
    $password = $_POST['password'];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    $sql =
        "INSERT INTO tblogininfo
        (`userEmail`,`username`,`password`)
        VALUES
        (?,?,?)";
    $sign_up = $conn->prepare($sql);
    $sign_up->bind_param('sss',$email,$username,$hash_password);
    
    if ($sign_up->execute()) {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        header('Location: register.php');
    } else {
        echo "
            <script>
                alert('Sign up unsuccessful.');
                window.location.href = 'register.php';
            </script>
        ";
    }

}

// User log in

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log_in'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];

    $check_user_email = checkDuplicateEmail($user);
    $check_user_username =  checkDuplicateUsername($user);

    if ($check_user_email || $check_user_username) {
        $sql =
            "SELECT password FROM tblogininfo WHERE userEmail='$user' OR username='$user'";
        $rs = $conn->query($sql);
        $hashed_password = $rs->fetch_assoc();

        if (password_verify($password, $hashed_password['password'])) {
            $_SESSION['user'] = $user;
            header('Location: home.php');
        } else {
            setcookie('login_error', 'Password does not match!');
            header('Location: index.php');
        }
    } else {
        setcookie('login_error', 'Email or username does not exist!');
        header('Location: index.php');
    }
}

?>