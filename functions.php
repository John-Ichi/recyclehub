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
            $deleted_logs[] = $row;
        }

        $output = json_encode($deleted_logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('deleted_logs.json', $output);
}

function getWarningLogs() {
    $conn = connect();

    $sql =
        "SELECT *
        FROM tbwarninglogs";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $warning_logs[] = $row;
        }

        $output = json_encode($warning_logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('warning_logs.json', $output);
}

function getBanLogs() {
    $conn = connect();

    $sql =
        "SELECT *
        FROM tbbanlogs";
    $rs = $conn->query($sql);

    if ($rs->num_rows === 0) {
        $output = json_encode(null);
    } else {
        while ($row = $rs->fetch_assoc()) {
            $ban_logs[] = $row;
        }

        $output = json_encode($ban_logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    file_put_contents('ban_logs.json', $output);
}

function postingError($error_msg) { // User posting error handler
    setcookie('post_error', $error_msg);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_notice'])) { // Confirm post deletion notice
    $user_id = $_POST['user_id'];

    $sql =
        "UPDATE tbpostsdeletionlog
        SET confirmed='1'
        WHERE userId='$user_id'";
    if ($conn->query($sql)) {
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_ban_warning'])) { // Ban warning confirmation
    $user_id = $_POST['user_id'];

    $sql =
        "UPDATE tbwarninglogs
        SET confirmed='1'
        WHERE userId='$user_id'";
    if ($conn->query($sql)) {
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['post_comment'])) { // Comment handler
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
        exit();
    }
}

getComments(); // Additional call

// Admin functions

function adminError($error_msg) { // Admin error handler
    setcookie('admin_error',$error_msg);
    header('Location: admin.php');
    exit();
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
        exit();
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
        exit();
    } else {
        $error_msg = 'Wrong password!';
        adminError($error_msg);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_register'])) { // Admin registration
    $admin_name = $_POST['admin_username'];
    $password = $_POST['admin_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) { // Check password
        $error_msg = 'Password does not match!';
        adminError($error_msg);
        exit();
    }

    if (checkDuplicateAdmin($admin_name) === 'true') {
        $error_msg = 'Admin already exists!';
        adminError($error_msg);
        exit();
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
        exit();
    } else {
        $error_msg = 'Registration unsuccesful.';
        adminError($error_msg);
        exit();
    }
}

function errorHandler($error_msg) { // Error handler for admin user_actions
    setcookie('post_deletion_error',$error_msg);
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['warn_user'])) { // User warning POST
    $user_id = $_POST['user_id'];
    $warning_message = $_POST['warning_message'];

    $sql =
        "INSERT INTO tbwarninglogs
        (`userId`,`warningMessage`)
        VALUES
        (?,?)";
    $warning = $conn->prepare($sql);
    $warning->bind_param('ss',$user_id,$warning_message);

    if ($warning->execute()) {
        setcookie('warning_successful', 'Warning posted successfully.');
        header('Location: user_actions.php?user=' . $user_id);
        exit();
    } else {
        $error_msg = 'Error posting warning.';
        errorHandler($error_msg);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) { // Admin delete post
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
                        header('Location: user_actions.php?user=' . $user_id);
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
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ban_user'])) { // Ban POST
    $user_id = $_POST['user_id'];
    $ban_message = $_POST['ban_message'];

    $sql =
        "INSERT INTO tbbanlogs
        (`userId`,`reason`)
        VALUES
        (?,?)";
    $ban = $conn->prepare($sql);
    $ban->bind_param('ss',$user_id,$ban_message);

    if ($ban->execute()) {
        setcookie('ban_successful', 'User banned successfully.');
        header('Location: user_actions.php?user=' . $user_id);
        exit();
    } else {
        $error_msg = 'Error banning user.';
        errorHandler($error_msg);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unban_user'])) { // Unban UPDATE
    $user_id = $_POST['user_id'];
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    $admin_check = checkDuplicateAdmin($admin_username);

    if ($admin_check === 'false') {
        $error_msg = 'Invalid admin username';
        errorHandler($error_msg);
        exit();
    }

    $sql =
        "SELECT password 
        FROM tbadmininfo
        WHERE adminName='$admin_username'";
    $rs = $conn->query($sql);

    if ($rs) {
        $hashed_password = $rs->fetch_assoc();
        
        if (password_verify($admin_password, $hashed_password['password'])) {
            $sql =
                "UPDATE tbbanlogs
                SET unban='1'
                WHERE userId='$user_id'";
            if ($conn->query($sql)) {
                setcookie('unban_succesful', 'User unbanned successfully');
                header('Location: user_actions.php?user=' . $user_id);
                exit();
            } else {
                $error_msg = 'Error unbanning user.';
                errorHandler($error_msg);
                exit();
            }
        } else {
            $error_msg = 'Wrong password.';
            errorHandler($error_msg);
            exit();
        }
    } else {
        $error_msg = 'Error validating password';
        errorHandler($error_msg);
        exit();
    }
}

?>