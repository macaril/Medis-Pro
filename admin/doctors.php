<?php

//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }

} else {
    header("location: ../login.php");
}


//import database
include("../connection.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">

    <title>Doctors</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@edoc.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-dashbord">
                        <a href="index.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Dashboard</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active">
                            <div>
                                <p class="menu-text">Doctors</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Schedule</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Appointment</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Patients</p>
                            </div>
                        </a>
                    </td>
                </tr>

            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width="13%">
                        <a href="doctors.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                <font class="tn-in-text">Back</font>
                            </button></a>
                    </td>
                    <td>

                        <form action="" method="post" class="header-search">

                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">&nbsp;&nbsp;

                            <?php
                            echo '<datalist id="doctors">';
                            // PERBAIKAN: Mengganti query() MySQLi dengan PDO query()
                            $list11 = $database->query("select docname,docemail from doctor;");

                            // PERBAIKAN: Mengganti num_rows dan fetch_assoc() dengan PDO fetch() loop
                            while ($row00 = $list11->fetch(PDO::FETCH_ASSOC)) {
                                $d = $row00["docname"];
                                $c = $row00["docemail"];
                                echo "<option value='$d'><br/>";
                                echo "<option value='$c'><br/>";
                            };

                            echo ' </datalist>';
                            ?>

                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">

                        </form>

                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php
                            date_default_timezone_set('Asia/Kolkata');

                            $date = date('Y-m-d');
                            echo $date;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>

                <tr >
                    <td colspan="2" style="padding-top:30px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Add New Doctor</p>
                    </td>
                    <td colspan="2">
                        <a href="?action=add&id=none&error=0" class="non-style-link"><button class="login-btn btn-primary btn button-icon" style="display: flex;justify-content: center;align-items: center;margin-left:75px;background-image: url('../img/icons/add.svg');">Add New</button>
                            </a></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Doctors (<?php echo $list11->rowCount(); // Menggunakan rowCount() dari query datalist ?>)</p>
                    </td>

                </tr>
                <?php
                $sqlmain = "select * from doctor order by docid desc";
                $params = [];

                if ($_POST) {
                    $keyword = $_POST["search"];
                    
                    // PERBAIKAN KRITIS: Menggunakan ILIKE untuk PostgreSQL dan Prepared Statement
                    // Simplified the numerous OR LIKE clauses into a single ILIKE
                    $sqlmain = "SELECT * FROM doctor WHERE docemail ILIKE ? OR docname ILIKE ? ORDER BY docid DESC";
                    $search_term = "%$keyword%";
                    $params = [$search_term, $search_term];
                }

                // Final Execution
                $stmt_result = $database->prepare($sqlmain);
                $stmt_result->execute($params);
                ?>

                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">


                                                Doctor Name

                                            </th>
                                            <th class="table-headin">
                                                Email
                                            </th>
                                            <th class="table-headin">

                                                Specialties

                                            </th>
                                            <th class="table-headin">

                                                Events

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        // PERBAIKAN: Mengganti num_rows dengan rowCount()
                                        if ($stmt_result->rowCount() == 0) {
                                            echo '<tr>
                                            <td colspan="4">
                                            <br><br><br><br>
                                            <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                            <a class="non-style-link" href="doctors.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Doctors &nbsp;</font></button>
                                            </a>
                                            </center>
                                            <br><br><br><br>
                                            </td>
                                            </tr>';

                                        } else {
                                            // PERBAIKAN: Mengganti loop MySQLi dengan PDO fetch()
                                            while ($row = $stmt_result->fetch(PDO::FETCH_ASSOC)) {
                                                $docid = $row["docid"];
                                                $name = $row["docname"];
                                                $email = $row["docemail"];
                                                $spe = $row["specialties"];
                                                
                                                // PERBAIKAN: Menggunakan PDO Prepared Statement untuk mengambil nama Specialties
                                                $spcil_res = $database->prepare("select sname from specialties where id=?");
                                                $spcil_res->execute([$spe]);
                                                $spcil_array = $spcil_res->fetch(PDO::FETCH_ASSOC);
                                                $spcil_name = $spcil_array["sname"];
                                                
                                                echo '<tr>
                                                    <td> &nbsp;' .
                                                    substr($name, 0, 30)
                                                    . '</td>
                                                    <td>
                                                    ' . substr($email, 0, 20) . '
                                                    </td>
                                                    <td>
                                                    ' . substr($spcil_name, 0, 20) . '
                                                    </td>

                                                    <td>
                                                    <div style="display:flex;justify-content: center;">
                                                    <a href="?action=edit&id=' . $docid . '&error=0" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-edit" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Edit</font></button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="?action=view&id=' . $docid . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="?action=drop&id=' . $docid . '&name=' . $name . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Remove</font></button></a>
                                                    </div>
                                                    </td>
                                                    </tr>';

                                            }
                                        }

                                        ?>

                                    </tbody>

                                </table>
                            </div>
                        </center>
                    </td>
                </tr>


            </table>
        </div>
    </div>
    <?php
    if ($_GET) {

        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'add') {
            $error_1 = $_GET["error"];
            $errorlist = array(
                '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
                '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>',
                '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                '4' => "",
                '0' => '',

            );
            if ($error_1 != '4') {
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    
                        <a class="close" href="doctors.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">' .
                $errorlist[$error_1]
                . '</td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Doctor.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <form action="add-new.php" method="POST" class="add-new-form">
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="name" class="input-text" placeholder="Doctor Name" required><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="email" name="email" class="input-text" placeholder="Email Address" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nik" class="form-label">nik: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="nik" class="input-text" placeholder="nik Number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Choose specialties: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="spec" id="" class="box">';


                // PERBAIKAN: Mengganti query() MySQLi dengan PDO query()
                $list11 = $database->query("select * from specialties order by sname asc;");


                // PERBAIKAN: Mengganti loop MySQLi dengan PDO fetch()
                while ($row00 = $list11->fetch(PDO::FETCH_ASSOC)) {
                    $sn = $row00["sname"];
                    $id00 = $row00["id"];
                    echo "<option value=" . $id00 . ">$sn</option><br/>";
                };


                echo '       </select><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="password" class="form-label">Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="password" class="input-text" placeholder="Defind a Password" required><br>
                                </td>
                            </tr><tr>
                                <td class="label-td" colspan="2">
                                    <label for="cpassword" class="form-label">Conform Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required><br>
                                </td>
                            </tr>
                            
                                
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Add" class="login-btn btn-primary btn">
                                </td>
                            
                            </tr>
                            
                            </form>
                            </tr>
                        </table>
                        </div>
                        </div>
                    </center>
                    <br><br>
                </div>
                </div>
                ';

            } else {
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br><br><br>
                        <h2>New Record Added Successfully!</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="doctors.php" class="non-style-link"><button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>

                        </div>
                        <br><br>
                    </center>
                </div>
                </div>
                ';
            }
        } elseif ($action == 'edit') {
            // PERBAIKAN: Konversi kueri pengambilan data dokter dari MySQLi ke PDO
            $sqlmain = "select * from doctor where docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $name = $row["docname"];
            $email = $row["docemail"];
            $spe = $row["specialties"];

            // PERBAIKAN: Konversi kueri specialties dari MySQLi ke PDO
            $spcil_res = $database->prepare("select sname from specialties where id=?");
            $spcil_res->execute([$spe]);
            $spcil_array = $spcil_res->fetch(PDO::FETCH_ASSOC);
            $spcil_name = $spcil_array["sname"];
            $nik = $row['docnic'];
            $tele = $row['doctel'];

            $error_1 = $_GET["error"];
            $errorlist = array(
                '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
                '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>',
                '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                '4' => '<label for="promter" class="form-label" style="color:rgb(46, 139, 87);text-align:center;">Record Updated Successfully.</label>',
                '0' => '',

            );

            if ($error_1 != '4') {
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    
                        <a class="close" href="doctors.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">' .
                $errorlist[$error_1]
                . '</td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Edit Doctor Details.</p>
                                    Doctor ID : ' . $id . ' (Auto Generated)<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <form action="edit-doc.php" method="POST" class="add-new-form">
                                    <label for="Email" class="form-label">Email: </label>
                                    <input type="hidden" value="' . $id . '" name="id00">
                                    <input type="hidden" name="oldemail" value="' . $email . '" >
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="email" name="email" class="input-text" placeholder="Email Address" value="' . $email . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="name" class="input-text" placeholder="Doctor Name" value="' . $name . '" required><br>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nik" class="form-label">nik: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="nik" class="input-text" placeholder="nik Number" value="' . $nik . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" value="' . $tele . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Choose specialties: (Current' . $spcil_name . ')</label>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="spec" id="" class="box">';


                // PERBAIKAN: Mengganti query() MySQLi dengan PDO query()
                $list11 = $database->query("select * from specialties order by sname asc;");


                // PERBAIKAN: Mengganti loop MySQLi dengan PDO fetch()
                while ($row00 = $list11->fetch(PDO::FETCH_ASSOC)) {
                    $sn = $row00["sname"];
                    $id00 = $row00["id"];
                    echo "<option value=" . $id00 . ">$sn</option><br/>";
                };


                echo '       </select><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="password" class="form-label">Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="password" class="input-text" placeholder="Defind a Password" required><br>
                                </td>
                            </tr><tr>
                                <td class="label-td" colspan="2">
                                    <label for="cpassword" class="form-label">Conform Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required><br>
                                </td>
                            </tr>
                            
                                
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Save" class="login-btn btn-primary btn">
                                </td>
                            
                            </tr>
                            
                            </form>
                            </tr>
                        </table>
                        </div>
                        </div>
                    </center>
                    <br><br>
                </div>
                </div>
                ';
            } else {
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br><br><br>
                        <h2>Edit Successfully!</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="doctors.php" class="non-style-link"><button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>

                        </div>
                        <br><br>
                    </center>
                </div>
                </div>
                ';
            }
        } elseif ($action == 'view') {
            // PERBAIKAN: Konversi kueri pengambilan data dokter dari MySQLi ke PDO
            $sqlmain = "select * from doctor where docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $name = $row["docname"];
            $email = $row["docemail"];
            $spe = $row["specialties"];

            // PERBAIKAN: Konversi kueri specialties dari MySQLi ke PDO
            $spcil_res = $database->prepare("select sname from specialties where id=?");
            $spcil_res->execute([$spe]);
            $spcil_array = $spcil_res->fetch(PDO::FETCH_ASSOC);
            $spcil_name = $spcil_array["sname"];
            $nik = $row['docnic'];
            $tele = $row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <h2></h2>
                    <a class="close" href="doctors.php">&times;</a>
                    <div class="content">
                        eDoc Web App<br>
                        
                    </div>
                    <div style="display: flex;justify-content: center;">
                    <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                    
                        <tr>
                            <td>
                                <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                            </td>
                        </tr>
                        
                        <tr>
                            
                            <td class="label-td" colspan="2">
                                <label for="name" class="form-label">Name: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                            ' . $name . '<br><br>
                            </td>
                            
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="Email" class="form-label">Email: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                            ' . $email . '<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="nik" class="form-label">nik: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                            ' . $nik . '<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="Tele" class="form-label">Telephone: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                            ' . $tele . '<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="spec" class="form-label">Specialties: </label>
                                
                            </td>
                        </tr>
                        <tr>
                        <td class="label-td" colspan="2">
                        ' . $spcil_name . '<br><br>
                        </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                            </td>
                        
                        </tr>
                        

                    </table>
                    </div>
                </center>
                <br><br>
            </div>
            </div>
            ';
        } elseif ($action == 'drop') {
            $nameget = $_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>(' . substr($nameget, 0, 40) . ').
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-doctor.php?id=' . $id . '" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="doctors.php" class="non-style-link"><button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            ';
        }
    }

    ?>
</div>

</body>
</html>