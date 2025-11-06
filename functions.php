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
function checkDuplicateEmail($email) {
    $conn = connect();
    
    $sql =
        "SELECT userEmail FROM tblogininfo WHERE userEmail='$email'";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) return true;
    else return false;
}

function checkDuplicateUsername($username) {
    $conn = connect();

    $sql =
        "SELECT username FROM tblogininfo WHERE username='$username'";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) return true;
    else return false;
}

function getAllUsers() {
    $conn = connect();

    $sql =
        "SELECT userId, userEmail, username FROM tblogininfo";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }

        $output = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('users.json', $output);
}

function getUserDetails($user) {
    $conn = connect();

    $sql =
        "SELECT tblogininfo.userId, tblogininfo.userEmail, tblogininfo.username, tbfollows.*
        FROM tblogininfo
        LEFT JOIN tbfollows
        ON tblogininfo.userId=tbfollows.follower
        WHERE userEmail='$user' OR username='$user'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $user_info[] = $row;
        }

        $output = json_encode($user_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('user_info.json', $output);
}

function getPosts() {
    $conn = connect();

    $sql =
        "SELECT tbimages.*, tbposts.content, tbposts.category, tblogininfo.username
        FROM tbimages
        LEFT JOIN tbposts
        ON tbimages.postId=tbposts.postId
        LEFT JOIN tblogininfo
        ON tbposts.userId=tblogininfo.userId";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $posts[] = $row;
        }

        $output = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('posts.json', $output);
}

function getComments() {
    $conn = connect();

    $sql =
        "SELECT tbcomments.*, tblogininfo.username
        FROM tbcomments
        LEFT JOIN tblogininfo
        ON tbcomments.userId=tblogininfo.userId";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $comments[] = $row;
        }

        $output = json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('comments.json', $output);
}

function getPostsDeletionLog() {
    $conn = connect();

    $sql =
        "SELECT *
        FROM tbpostsdeletionlog";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $logs[] = $row;
        }

        $output = json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('logs.json', $output);
}

function postingError($error_msg) {
    setcookie('post_error', $error_msg); // Use this cookie to return an error message when redirected
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign_up'])) { // User sign up
    $email = $_POST['email'];
    $username = $_POST['username'];
    
    // Check for duplicate email and username
    $check_email = checkDuplicateEmail($email);
    $check_username = checkDuplicateUsername($username);
    if ($check_email == true || $check_username == true) {
        setcookie('duplicate_email',$email);
        setcookie('duplicate_username',$username);
        header('Location: register.php');
        return;
    }
    
    // Hash password
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        setcookie('wrong_password',$confirm_password);
        header('Location: register.php');
        return;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql =
        "INSERT INTO tblogininfo
        (`userEmail`,`username`,`password`)
        VALUES
        (?,?,?)";
    $sign_up = $conn->prepare($sql);
    $sign_up->bind_param('sss',$email,$username,$hashed_password);
    
    if ($sign_up->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['useremail'] = $email;
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log_in'])) { // User log in
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
            setcookie('login_error', 'Wrong password!');
            header('Location: index.php');
        }
    } else {
        setcookie('login_error', 'Email or username does not exist!');
        header('Location: index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_post'])) { // Create post

    $user_id = $_POST['user_id'];
    $post_category = $_POST['category'];
    $post_location = $_POST['location'];

    $upload_directory = 'uploads/';
    $allowed_extensions = array('jpg','jpeg','png');
    $allowed_MIME_types = array('image/jpeg','image/png');

    if (!empty($_FILES['images'])) { // Check if $_FILES is empty/isset
        
        if (!is_dir($upload_directory)) { // Check directory
            mkdir($upload_directory, 0755, true);
        }

        $MAX_FILE_SIZE = 10 * 1024 * 1024;
        $MAX_NUM_OF_POSTS = 5;

        if (count($_FILES['images']['name']) > $MAX_NUM_OF_POSTS) { // Validate number of posts
            postingError('The maximum number of uploads is ten (10).');
        }

        foreach ($_FILES['images']['tmp_name'] as $index => $temp_name) { // Validation
            $original_name = $_FILES['images']['name'][$index];
            $file_type = $_FILES['images']['type'][$index];
            $file_size = $_FILES['images']['size'][$index];
            $error = $_FILES['images']['error'][$index];

            if ($error !== 0) { // Validate errors
                postingError('Error uploading files.');
            }

            if ($file_size > $MAX_FILE_SIZE) { // Validate file size
                postingError('The maximum file size allowed is 10MB.');
            }

            $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);

            if (!in_array($file_extension, $allowed_extensions) || !in_array($file_type, $allowed_MIME_types)) { // Validate file type
                postingError('Invalid file type.');
            }
        }

        $sql =
            "INSERT INTO tbposts
            (`content`,`userId`,`category`)
            VALUES
            (?,?,?)";
        $post_content = $conn->prepare($sql);
        $post_content->bind_param('sss', $_POST['text_content'],$user_id,$post_category);

        if ($post_content->execute()) { // Upload post content/text

            $sql =
                "SELECT postId FROM tbposts ORDER BY postId DESC LIMIT 1"; // Get most recent post (ID)
            $rs = $conn->query($sql);
            $post_id = $rs->fetch_assoc();

            foreach ($_FILES['images']['tmp_name'] as $index => $temp_name) {

                $original_name = $_FILES['images']['name'][$index];
                $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
                
                $new_file_name = uniqid('img_', true) . '.' . $file_extension;
                $destination = $upload_directory . $new_file_name;

                if (move_uploaded_file($temp_name, $destination)) { // Errors and file type validated
                    
                    $sql =
                        "INSERT INTO tbimages
                        (`postId`,`image`,`userId`)
                        VALUES
                        (?,?,?)";
                    $upload_images = $conn->prepare($sql);
                    $upload_images->bind_param('sss',$post_id['postId'],$destination,$user_id);
                    
                    if ($upload_images->execute()) {
                        if ($post_location == 'home_page') {
                            header('Location: home.php');
                        } else if ($post_location == 'profile_page') {
                            header('Location: profile.php');
                        }
                    } else {
                        postingError('Error uploading files.');
                    }
                } else {
                    postingError('Error uploading files.');
                }
            }
        } else {
            postingError('Error uploading files.');
        }
    } else {
        postingError('Error uploading files');
    }
}

function adminError($error_msg) {
    setcookie('admin_error',$error_msg);
    header('Location: admin.php');
    exit;
}

function checkDuplicateAdmin($admin_name) {
    $conn = connect();

    $sql =
        "SELECT adminName
        FROM tbadmininfo
        WHERE adminName='$admin_name'";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $response = 'false';
        return $response;
    } else {
        $response = 'true';
        return $response;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) { // Admin login
    $admin = $_POST['admin'];
    $password = $_POST['password'];

    if (checkDuplicateAdmin($admin) === 'false') {
        $error_msg = "Admin does not exist!";
        adminError($error_msg);
        exit;
    }

    $sql =
        "SELECT password
        FROM tbadmininfo
        WHERE adminName='$admin'";
    $rs = $conn->query($sql);
    $hashed_password = $rs->fetch_assoc();

    if (password_verify($password,$hashed_password['password'])) {
        $_SESSION['admin'] = $admin;
        header('Location: dashboard.php');
        exit;
    } else {
        $error_msg = 'Wrong password!';
        adminError($error_msg);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_register'])) { // Admin registration
    $admin_name = $_POST['admin_username'];
    $password = $_POST['admin_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) { // Check password
        $error_msg = 'Password does not match!';
        adminError($error_msg);
        exit;
    }

    if (checkDuplicateAdmin($admin_name) === 'true') {
        $error_msg = 'Admin already exists!';
        adminError($error_msg);
        exit;
    }

    $hashed_password = password_hash($password,PASSWORD_DEFAULT);

    $sql =
        "INSERT INTO tbadmininfo
        (`adminName`,`password`)
        VALUES
        (?,?)";
    $register = $conn->prepare($sql);
    $register->bind_param('ss',$admin_name,$hashed_password);        

    if ($register->execute()) {
        header('Location: admin.php');
        exit;
    } else {
        $error_msg = 'Registraion unsuccesful.';
        adminError($error_msg);
        exit;
    }
}

?>