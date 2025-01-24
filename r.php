<?php
require('conn.php');

if (isset($_POST['register'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $usn = mysqli_real_escape_string($conn, $_POST['usn']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $user_exist_query = "SELECT * FROM s_info WHERE email='$email' OR usn='$usn'";
    $result = mysqli_query($conn, $user_exist_query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch['usn'] == $usn) {
                echo "
                <script>
                    alert('USN is already used');
                    window.location.href='re.html';
                </script>";
            } else {
                echo "
                <script>
                    alert('Email is already used');
                    window.location.href='re.html';
                </script>";
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO s_info (fname, usn, email, password) VALUES ('$fname', '$usn', '$email', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                echo "
                <script>
                    alert('Registered Successfully');
                    window.location.href='log.html';
                </script>";
            } else {
                echo "
                <script>
                    alert('Cannot run query');
                    window.location.href='re.html';
                </script>";
            }
        }
    } else {
        echo "
        <script>
            alert('Database error: Cannot run query');
            window.location.href='re.html';
        </script>";
    }
}
?>
