<?php
session_start();
include('../functions/execute-sql.php');
include('../models/punches-model.php');
include("../functions/validation.php");
validation();
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$timeclock = new mysqli($server, $username, $password, 'timeclock');

$prettyStart = strtotime($_GET['start']);
$prettyStart = date("m-d-Y", $prettyStart);
$prettyEnd = strtotime($_GET['end']);
$prettyEnd = date("m-d-Y", $prettyEnd);

$punchModel = new Punches($timeclock);
$workingEmployees = $_SESSION['workingEmployees'];




?>
<?php
include('../header.php');
?>
<html>

<body style="margin: 0;" onload="window.resizeTo(825, 950)">
    <div class="manager-report">
        <p class="center"></p>



        <div style="text-align:left; padding-left: 25px;padding-right: 25px;">
            <div style="float:right;width: 210px;text-align: center;">

                <button onclick="window.location = '/timeclock/'" class="button" style="width: 100px;margin-right: 5px;display: inline-block;float: left;">
                    Done
                </button>

                <button class="button" style="width: 100px;display: inline-block; float: right;" onclick="window.location='./manager-report-search.php'">
                    New
                </button>

            </div>
            Time Clock Report

            <br />
            <?php
            echo "{$prettyStart} ... {$prettyEnd}";
            ?>
        </div>

        <?php

        foreach ($workingEmployees as $employee) {
            $employeeid = $employee['employeeid'];
            $name = "{$employee['fname']} {$employee['lname']}";
            $departments = explode(',', $employee['department']);

            $grandHours = 0;
            echo '<hr style="clear:both;">';

            echo <<<"EOL"
                    <span style="font-weight: bold;font-size: 1em;margin-left: 25px;">
                        {$name} 
                    </span>
                    
                    <p></p>
                EOL;
            foreach ($departments as $department) {

                $times = $punchModel->getPunches($employeeid, $_GET['start'], $_GET['end'], $department);
                $totalTime = $punchModel->getTotalTime($employeeid, $_GET['start'], $_GET['end'], $department)[0];
                if (count($times) == 0) continue;

                $grandHours += $totalTime['total_time'];

                echo '
                <table class="manager-report-table">
          
                    <tr>
                        <th>IN</th>
                        <th>OUT</th>
                        <th colspan="2">TIME</th>
                    </tr>
                ';

                foreach ($times as $time) {

                    $row = $time;

                    $currentDepartment = $row['department'];

                    $isoInTime = date('Y-m-d\TH:i', strtotime($row["time_in"]));
                    $isoOutTime = ($row['time_out'] == null) ? null : date('Y-m-d\TH:i', strtotime($row["time_out"]));
                    // start of table row for punch
                    echo <<< "EOL"
                        <tr>
                            <td> 
                                {$row["time_in"]} 
                            </td>
                    EOL;

                    if ($row['time_out'] > 0) {

                        echo "<td>{$row['time_out']}</td>";
                    } else {
                        echo '<td>--</td>';
                    }
                    if ($row['time_out'] > 0) {

                        echo "
                        <td>
                            <font style='color: gray;font-style: italic;''> 
                                {$row["total_minutes"]} 
                            </font>

                        </td>

                        <td>
                            {$row["total_time"]}
                        </td>";
                    } else {
                        echo '
                        <td>
                            <font style="color: gray;font-style: italic;"> 
                                --
                            </font>
                        </td>

                        <td>
                            --
                        </td>';
                    }


                    echo <<< "EOL"
                    <td>
                        
                        
                            <button class="edit-button" data-name="{$name}" onclick="openPunchModal(this,'{$row["punchid"]}','{$currentDepartment}','{$employee["department"]}')" data-start="{$isoInTime}" data-end="{$isoOutTime}"/>
                        

                    </td>
                    
                    EOL;
                    echo "<td>";
                    if ($row['total_time'] > 12) {
                        echo "<---that's a lot!!";
                    }
                    echo '</td>';

                    echo "</tr>";
                    // end of punch row
                }

                echo "<tr>";


                echo "<td colspan='6' style='text-align: right;font-size: 1.1em; width: 100%;margin: auto; padding-top: 8px; padding-right:170px; background:none; border:none; color:gray;' >";

                echo $currentDepartment . " Hrs - ";




                $sec = explode(".", $totalTime['total_time'])[1];
                $hours = explode(".", $totalTime['total_time'])[0];
                $hours = number_format($hours);
                $sec = round($sec / 10 * 60);
                if ($sec < 10) {
                    $sec = "0" . $sec;
                }
                if ($sec > 99) {
                    $sec = round($sec * .1);
                }

                echo  "$hours:" . $sec . " - ";

                echo $totalTime["total_time"];

                echo "</td>";

                echo "</tr>";
                echo '</table>';
            }
            echo <<<"EOL"
                <div style='width:90%; font-weight:bold;margin:auto;text-align:right; padding-top:10px;'>
                <div style='display:flex;justify-content: space-between;align-items:center;'>
                <button class='no-print' data-name="{$name}" onclick='newPunch(this,"{$employee['employeeid']}","{$employee['department']}")'>New punch</button>
            EOL;

            echo "<span>";
            echo "Total Hours - " . number_format($grandHours, 2);
            echo "</span> </div>";
            echo "</div>";
        }

        ?>

        <p></p>
    </div>
    <?php
    include("../punch-modal.php");
    ?>
</body>

</html>