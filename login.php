<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'clg';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = trim($_POST['log']); // Email or USN
    $password = trim($_POST['password']);

    // Check if the user exists in the database
    $sql = "SELECT * FROM s_info WHERE email = ? OR usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $loginInput, $loginInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        //if (password_verify($password, $user['password']))
        if($password===$user['password'])
         { echo"valid";
            // Store user information in the session
           $_SESSION['usn'] = $user['usn'];
            $_SESSION['fname'] = $user['fname'];
            header("Location: home.html"); // Redirect to home page
            exit();
        }
         else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email or USN.";
    }
    $stmt->close();
}
$conn->close();
?>
