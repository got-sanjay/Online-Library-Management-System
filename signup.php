<?php
// signup.php
include("db_connect.php"); //  DB connection
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memberID = $conn->real_escape_string($_POST['memberID']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // matches passMD5 column
    $signupDate = date('Y-m-d');
    $groupID = 3; // Assuming 2 = 'Members' group, change as needed
    $isBanned = 0;
    $isApproved = 1; // Change to 0 if you want admin to approve users
    $custom1 = $conn->real_escape_string($_POST['custom1']);
    $custom2 = $conn->real_escape_string($_POST['custom2']);
    $custom3 = '';
    $custom4 = '';
    $comments = '';
    $pass_reset_key = '';
    $pass_reset_expiry = NULL;

    $check = $conn->query("SELECT * FROM membership_users WHERE memberID='$memberID'");
    if ($check->num_rows > 0) {
        $msg = "Username already taken.";
    } else {
        $sql = "INSERT INTO membership_users 
        (memberID, passMD5, email, signupDate, groupID, isBanned, isApproved,
         custom1, custom2, custom3, custom4, comments, pass_reset_key, pass_reset_expiry) 
        VALUES 
        ('$memberID', '$password', '$email', '$signupDate', $groupID, $isBanned, $isApproved,
         '$custom1', '$custom2', '$custom3', '$custom4', '$comments', '$pass_reset_key', NULL)";
        
        if ($conn->query($sql)) {
            header("Location: index.php?signIn=1&registered=1");
            exit;
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library User Registration</title>
    <style>
        body { font-family: Arial; background: #f1f1f1; padding: 50px; }
        .form-box { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: green; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Sign Up</h2>
    <form method="POST" action="">
        <input type="text" name="memberID" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="text" name="custom1" placeholder="Full Name" />
        <input type="text" name="custom2" placeholder="Phone Number" />
        <button type="submit">Register</button>
    </form>
    <p style="color: red;"><?= $msg ?></p>
</div>
</body>
</html>
