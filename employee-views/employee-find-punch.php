<?php
session_start();
$today = date('Y-m-d');
$lastWeek = date("Y-m-d", strtotime("$today -1 week"));

?>
<html>

<?php
include('../header.php');
?>

<body>
    <div class="entry-div">
        <p class="center">
            <span style="font-size: 1.7em;">Enter Date Range</span>
        </p>
        <p class="center" style="font-size:1.1em;">
            <?php echo $_SESSION['employee']['fullName']; ?>
        </p>
        <img src="../images/find-icon.png" style="float:right;display: block;height: 50px;">
        <div style="margin: auto; width: 60%;margin-top: 10px;">
            <form method="POST" action="./mytime-view.php">

                <label for="from">From</label>
                <br />
                <input type="date" id="from" name="startdate" value="<?php echo $lastWeek; ?>" placeholder="From"><br />
                <label for="to">To</label>
                <br />
                <input type="date" id="to" name="enddate" value="<?php echo $today; ?>" placeholder="To">

                <p style="display: flex;">
                    <button onclick="window.location = '/timeclock/'" type="button" class="cancel" style="width: 49%;">Cancel</button>
                    <button type="submit" class="button" style="width: 49%;">Continue</button>
                </p>
            </form>

        </div>


    </div>

</html>