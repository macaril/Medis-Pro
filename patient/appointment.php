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

    <title>My Appointments</title>
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
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu">
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
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
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
                        <a href="appointment.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                <font class="tn-in-text">Back</font>
                            </button></a>
                    </td>
                    <td>
                        <p style="font-size: 20px;font-weight:900;padding-left:40px;" class="heading-main12">My Bookings</p>
                    </td>
                    <td width="20%">
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
                // Logika kueri utama
                $sqlmain = "SELECT appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate
                            FROM schedule 
                            INNER JOIN doctor ON schedule.docid::text = doctor.docid::text 
                            INNER JOIN appointment ON schedule.scheduleid = appointment.scheduleid
                            WHERE appointment.pid = $userid
                            ORDER BY appointment.appodate DESC";
                
                // Mengganti MySQLi query() dengan PDO query()
                $result = $database->query($sqlmain);
                
                $header = "All Bookings";

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
                                            <th class="table-head-1">Appointment Number</th>
                                            <th class="table-head-2">Session Title</th>
                                            <th class="table-head-3">Doctor</th>
                                            <th class="table-head-4">Scheduled Date</th>
                                            <th class="table-head-5">Scheduled Time</th>
                                            <th class="table-head-6">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        // PERBAIKAN: Mengganti num_rows dengan rowCount()
                                        if ($result->rowCount() == 0) {
                                            echo '<tr>
                                            <td colspan="6">
                                            <br><br><br><br>
                                            <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">You havent made any appointment yet!</p>
                                            <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Schedule a Session &nbsp;</font></button></a>
                                            </center>
                                            <br><br><br><br>
                                            </td>
                                            </tr>';
                                        } else {
                                            // Loop PDO fetch()
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                $appoid = $row["appoid"];
                                                $scheduleid = $row["scheduleid"];
                                                $title = $row["title"];
                                                $docname = $row["docname"];
                                                $scheduledate = $row["scheduledate"];
                                                $scheduletime = $row["scheduletime"];
                                                $apponum = $row["apponum"];
                                                
                                                echo '<tr>
                                                    <td>' . $apponum . '</td>
                                                    <td>' . substr($title, 0, 30) . '</td>
                                                    <td>' . substr($docname, 0, 30) . '</td>
                                                    <td>' . substr($scheduledate, 0, 10) . '</td>
                                                    <td>' . substr($scheduletime, 0, 5) . '</td>
                                                    <td>
                                                    <div style="display:flex;justify-content: center;">
                                                        <a href="?action=drop&id=' . $appoid . '&title=' . $title . '&docname=' . $docname . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel Booking</font></button></a>
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

            <?php
            // Logika popup konfirmasi hapus
            if ($_GET) {
                $action = $_GET["action"];
                if ($action == 'drop') {
                    $id = $_GET["id"];
                    $title = $_GET["title"];
                    $docname = $_GET["docname"];

                    echo '
                    <div id="popup1" class="overlay">
                        <div class="popup">
                            <a class="close" href="appointment.php">&times;</a>
                            <div class="content">
                                <img src="../img/icons/delete-white.svg" alt="Delete Icon" style="width: 45px; height: 45px;">
                                <br><br>
                                <p class="heading-main12" style="font-size: 18px; color: rgb(49, 49, 49); margin-top: 10px;">Are you sure?</p>
                                <p class="sub-title" style="font-size: 14px; color: rgb(119, 119, 119); margin-top: 0px;">
                                    You want to cancel your Appointment for:<br> (' . $title . ') with Dr. ' . $docname . '
                                </p>
                                <center>
                                    <table border="0" style="width: 80%; margin-top: 20px;">
                                        <tr>
                                            <td style="width: 50%;">
                                                <a href="delete-appointment.php?id=' . $id . '&action=drop" class="non-style-link"><button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;width:100%;">
                                                    <font class="tn-in-text">&nbsp;Yes, Cancel &nbsp;</font>
                                                </button></a>
                                            </td>
                                            <td style="width: 50%;">
                                                <a href="appointment.php" class="non-style-link"><button class="btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;width:100%;">
                                                    <font class="tn-in-text">&nbsp;&nbsp;No, Keep &nbsp;&nbsp;</font>
                                                </button></a>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }
            ?>
        </div>
    </div>
</body>

</html>