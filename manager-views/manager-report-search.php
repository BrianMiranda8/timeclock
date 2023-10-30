<?php
session_start();
include("../functions/validation.php");
validation();
$today = date("Y-m-d");
$lastWeek = date("Y-m-d", strtotime("$time -1 week"));
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$database = 'timeclock';


$conn = new mysqli($server, $username, $password, $database);

?>



<?php
include('../header.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<div class="entry-div">
    <div style="margin: auto;height: 100%; width: 60%;margin-top: 10px;padding-top: 10px;">
        <form method="post" action="../process/manager-report-process.php">
            <div style="margin-bottom: 80px;">
                <select id="id" onchange="getEmployeeId();" class="select-employee" name="employeeid">
                    <option selected value="-1">Full Report</option>
                    <?php
                    include('../functions/employee-datalist.php');
                    buildEmployeeList($conn);
                    ?>
                </select>
            </div>
            <label for="from">From</label>
            <br>
            <input type="date" id="from" name="startdate" value="<?php echo $lastWeek; ?>" placeholder="From">
            <br>
            <label for="to">To</label>
            <br>
            <input type="date" id="to" name="enddate" value="<?php echo $today; ?>" placeholder="To">
            <p style="display: flex;">
                <button onclick="window.location = '/timeclock/index.php';" type="button" class="cancel" style="width: 49%;">
                    Cancel
                </button>
                <button type="submit" class="button" style="width: 49%;">
                    Continue
                </button>

            </p>
        </form>
    </div>
    <script type="text/javascript" src="../js/timeclock.js"></script>
</div>