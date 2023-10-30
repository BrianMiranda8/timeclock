<?php
session_start();
include('../functions/validation.php');
validation();
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$database = 'timeclock';


$timeclock = new mysqli($server, $username, $password, $database);


if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

include("../functions/employee-datalist.php");

?>
<html>
<?php
include('../header.php');
?>

<body onload="window.resizeTo(715,440);">
    <div class="time-report">
        <p style="text-align:center;font-size: 1.2em;">Select an Employee or Select New<br />



        <form method="POST" action="../process/manager-employee-search.php">
            <select name="employeeid" class="select-employee-edit" style="margin-left: 50;display: inline;margin-right: 10px;" id="id">

                <option value="-1" selected>New Employee</option>

                <?php

                buildEmployeeList($timeclock);
                ?>

            </select>
            <input type="submit" class="button" style="width: 22%;">
            <button class="cancel" style="width: 22%;margin-left: 8px;color: white;" onclick="event.preventDefault();window.location = '/timeclock/';">
                Cancel
            </button>
        </form>
    </div>
</body>

</html>