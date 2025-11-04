<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"] == "") or $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}


//import database
include("../connection.php");

// PERBAIKAN: Konversi dari MySQLi Prepared Statement ke PDO
$sqlmain = "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->execute([$useremail]);
$userfetch = $stmt->fetch(PDO::FETCH_ASSOC);
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

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
                                    <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
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
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Home</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active">
                            <div>
                                <p class="menu-text">All Doctors</p>
                            </div>
                        </a>
                    </td>
                </tr>

                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Scheduled Sessions</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">My Bookings</p>
                            </div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Settings</p>
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

                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">
                            &nbsp;&nbsp;

                            <?php
                            echo '<datalist id="doctors">';
                            // Mengganti MySQLi query() dengan PDO query()
                            $list11 = $database->query("select docname,docemail from doctor;");

                            for ($y = 0; $y < $list11->rowCount(); $y++) { // PDO: Mengganti num_rows dengan rowCount()
                                $row00 = $list11->fetch(PDO::FETCH_ASSOC); // PDO: Mengganti fetch_assoc()
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
                            <?php echo $today; ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>

                <?php
                // Logika pencarian
                $sqlmain = "select * from doctor order by docid desc";
                if ($_POST) {
                    $keyword = $_POST["search"];
                    // PERBAIKAN: Menggunakan ILIKE untuk PostgreSQL dan Prepared Statement
                    $sqlmain = "SELECT * FROM doctor WHERE docname ILIKE ? OR docemail ILIKE ? ORDER BY docid DESC";
                    $stmt = $database->prepare($sqlmain);
                    // Tambahkan wildcard % untuk pencarian (diterapkan di parameter execute)
                    $search_term = "%$keyword%";
                    $stmt->execute([$search_term, $search_term]);
                    $result = $stmt;
                    $header = "Search Result for '" . $keyword . "'";
                } else {
                    // Jika tidak ada pencarian, gunakan query mentah (tetap diubah ke PDO query)
                    $result = $database->query($sqlmain);
                    $header = "All Doctors";
                }

                ?>

                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $header . " (" . $result->rowCount() . ")"; ?></p>
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-head-1">Doctor Name</th>
                                            <th class="table-head-2">Email</th>
                                            <th class="table-head-3">Specialties</th>
                                            <th class="table-head-4">Telephone</th>
                                            <th class="table-head-5">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($result->rowCount() == 0) {
                                            echo '<tr>
                                            <td colspan="5">
                                            <br><br><br><br>
                                            <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldnt find anything related to your keywords !</p>
                                            <a class="non-style-link" href="doctors.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Doctors &nbsp;</font></button></a>
                                            </center>
                                            <br><br><br><br>
                                            </td>
                                            </tr>';
                                        } else {
                                            // Loop PDO fetch()
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                $docid = $row["docid"];
                                                $name = $row["docname"];
                                                $email = $row["docemail"];
                                                $spe = $row["specialties"];
                                                $tele = $row["doctel"];

                                                $spcil_res = $database->query("SELECT sname FROM specialties WHERE id=$spe");
                                                $spcil_array = $spcil_res->fetch(PDO::FETCH_ASSOC);
                                                $spcil_name = $spcil_array["sname"];

                                                echo '<tr>
                                                    <td>' . substr($name, 0, 30) . '</td>
                                                    <td>' . substr($email, 0, 20) . '</td>
                                                    <td>' . substr($spcil_name, 0, 20) . '</td>
                                                    <td>' . substr($tele, 0, 15) . '</td>
                                                    <td>
                                                    <div style="display:flex;justify-content: center;">
                                                        <a href="schedule.php?id=' . $docid . '&name=' . $name . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View Sessions</font></button></a>
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
</body>

</html>