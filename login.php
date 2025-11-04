<?php

//learn from w3schools.com
//Unset all the server side variables

session_start();

$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');

$_SESSION["date"] = $date;


//import database
include("connection.php");





if ($_POST) {

    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    $error = '<label for="promter" class="form-label"></label>';

    // Check 1: Check if email exists in webuser (PDO Prepared Statement)
    $sql_webuser = "SELECT * FROM webuser WHERE email = ? LIMIT 1";
    $stmt_webuser = $database->prepare($sql_webuser);
    $stmt_webuser->execute([$email]);
    $user = $stmt_webuser->fetch(PDO::FETCH_ASSOC); // Ambil data user

    if ($user) { // Jika $user ditemukan
        $utype = $user['usertype']; // Ambil usertype dari array yang sudah diambil

        if ($utype == 'p') {
            // Check 2: Patient credentials (PDO Prepared Statement)
            $sql_patient = "SELECT * FROM patient WHERE pemail=? AND ppassword=?";
            $stmt_patient = $database->prepare($sql_patient);
            $stmt_patient->execute([$email, $password]);
            $checker = $stmt_patient->fetch(PDO::FETCH_ASSOC); // Cek kecocokan

            if ($checker) {
                //   Patient dashbord
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';
                header('location: patient/index.php');
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'a') {
            // Check 3: Admin credentials (PDO Prepared Statement)
            $sql_admin = "SELECT * FROM admin WHERE aemail=? AND apassword=?";
            $stmt_admin = $database->prepare($sql_admin);
            $stmt_admin->execute([$email, $password]);
            $checker = $stmt_admin->fetch(PDO::FETCH_ASSOC);

            if ($checker) {
                //   Admin dashbord
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                header('location: admin/index.php');
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'd') {
            // Check 4: Doctor credentials (PDO Prepared Statement)
            $sql_doctor = "SELECT * FROM doctor WHERE docemail=? AND docpassword=?";
            $stmt_doctor = $database->prepare($sql_doctor);
            $stmt_doctor->execute([$email, $password]);
            $checker = $stmt_doctor->fetch(PDO::FETCH_ASSOC);

            if ($checker) {
                //   doctor dashbord
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                header('location: doctor/index.php');
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any acount for this email.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label">&nbsp;</label>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">

    <title>Login</title>



</head>

<body>






    <center>
        <div class="container">
            <table border="0" style="margin: 0;padding: 0;width: 60%;">
                <tr>
                    <td>
                        <p class="header-text">Welcome Back!</p>
                    </td>
                </tr>
                <div class="form-body">
                    <tr>
                        <td>
                            <p class="sub-text">Login with your details to continue</p>
                        </td>
                    </tr>
                    <tr>
                        <form action="" method="POST">
                            <td class="label-td">
                                <label for="useremail" class="form-label">Email: </label>
                            </td>
                    </tr>
                    <tr>
                        <td class="label-td">
                            <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td">
                            <label for="userpassword" class="form-label">Password: </label>
                        </td>
                    </tr>

                    <tr>
                        <td class="label-td">
                            <input type="Password" name="userpassword" class="input-text" placeholder="Password" required>
                        </td>
                    </tr>


                    <tr>
                        <td><br>
                            <?php echo $error ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="submit" value="Login" class="login-btn btn-primary btn">
                        </td>
                    </tr>
                </div>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>




                </form>
            </table>

        </div>
    </center>
</body>

</html>