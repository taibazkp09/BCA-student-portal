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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = trim($_POST['fname']);
    $email= trim($_POST['email']);
    $usn = trim($_POST['usn']);
    $password = trim($_POST['password']);

    // Validation: Check if all fields are filled
    if (empty($fname) || empty($email) || empty($usn) || empty($password)) {
        echo "
        <script>
            alert('All fields are required. Please fill out the form completely');
             window.location.href='re.html';
            
        </script>";
       // die("All fields are required. Please fill out the form completely.");
    }

    // Validation: Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
     {
        echo "
        <script>
            alert(' Invalid email format ');
             window.location.href='re.html';
        </script>";
      //  die("Invalid email format.");
    }

    // Validation: Check USN pattern
 // $pattern="/^SG\d{2}BCA\d{3}";
 
    if(!preg_match('/^SG\d{2}BCA\d{3}$/i',$usn)) 
    {
       echo "
        <script>
        alert('Invalid email format  Invalid USN format. USN should start with \"SG\"followed by your admission year \"BCA\" followed by 3 digits (e.g., SG23BCA123).');
         window.location.href='re.html';
    </script>";
   //die(" Invalid email format  Invalid USN format. USN should start with 'SG'followed by your admission year 'BCA' followed by 3 digits (e.g., SG23BCA123).");
    }
    // Validation: Check if email or USN already exists in the database
    
    $sql = "SELECT * FROM s_info WHERE email = ? OR usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $usn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo "
        <script>
        alert('Email or USN already exists. Please use a unique email and USN. ');
         window.location.href='re.html';
    </script>";
       // die("Email or USN already exists. Please use a unique email and USN.");
    }

    // Hash the password for security
  //  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $sql = "INSERT INTO s_info (fname,email,usn, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fname, $email, $usn, $password);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to login page...";
        header("refresh:2; url=log.html"); // Redirect to login page after 2 seconds
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

