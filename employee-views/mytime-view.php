<?php
session_start();
include("../models/punches-model.php");
include("../functions/time-table.php");
$prettyStart = strtotime($_POST['startdate']);
$prettyStart = date("m-d-Y", $prettyStart);
$prettyEnd = strtotime($_POST['enddate']);
$prettyEnd = date("m-d-Y", $prettyEnd);

require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$timeclock = new mysqli($server, $username, $password, 'timeclock');

$punches = new Punches($timeclock);
$id = $_SESSION['employee']['employeeid'];

?>
<html>

<?php
include('../header.php');
?>

<body onload="window.resizeTo(825, 950);">
    <div class="time-report">
        <p class="center">
        <div style="width: 500px;margin-bottom: 5px;padding: 5px;text-align:center;">
            <button onclick="window.location = '/timeclock/'" type="button" class="button" style="width: 75px;display: block;float: right;">Done</button>
            <?php echo $_SESSION['employee']['fullName']; ?>
            <br />
            Date Range:
            <?php
            echo "$prettyStart" . ' ... ';
            echo "$prettyEnd";

            ?>
        </div>

        <?php

        foreach ($_SESSION['employee']['department'] as $department) {

            $totaltime = $punches->getTotalTime($id, $_POST['startdate'], $_POST['enddate'], $department);

            $totalDepTime += $totaltime[0]['total_time'];

            $times = $punches->getPunches($id, $_POST['startdate'], $_POST['enddate'], $department);
            if ($totaltime[0]['total_time'] == 0) continue;
            buildTimeTable($times, $totaltime, $department);
        }
        ?>

        <hr>
        <div style="color: blue;font-size: 1.1em;text-align: right;padding-right: 10px;">
            <?php
            $sec = explode(".", $totalDepTime)[1];
            $hours = explode(".", $totalDepTime)[0];
            $sec = round($sec / 100 * 60);
            if ($sec < 10) {
                $sec = "0$sec";
            }
            echo 'Grand Total Time: ' . "$hours:" . $sec . " - ";
            echo number_format($totalDepTime, 2);

            ?>




        </div>

    </div>
</body>

</html>