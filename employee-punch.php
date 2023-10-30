<?php
session_start();
include("./models/punches-model.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';

$timeclock = new mysqli($server, $username, $password, 'timeclock');

$punches = new Punches($timeclock);

$lastPunch = $punches->isClockedIn($_SESSION['employee']['employeeid']);
$isClocked = is_array($lastPunch);
$currentlyClockedDep = ($isClocked) ? $lastPunch['department'] : "";
$departments = $_SESSION['employee']['department'];
$currentDepIndex = array_search($currentlyClockedDep, $departments);
$prettyTime = date("l jS \of F Y h:i A");


?>
<!DOCTYPE html>
<html lang="en">

<?php
include('./header.php');
?>
<!-- Punch View Style -->
<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        height: 60%;
    }

    .button-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
    }

    .inner-btn-container {
        width: 100%;
        display: flex;
        margin-top: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .inner-btn-container>button {
        font-size: .875rem;
        flex: 1;
        min-width: 150px;
    }

    .datetime-container {
        top: 150px;
        left: 27px;
        margin: auto;
        text-align: center;
        font-size: 1.1em;
        width: 244px;
    }
</style>

<body>

    <div class="entry-div">

        <p class="center" style="font-size:1.1em;">
            <?php echo $_SESSION['employee']['fullName']; ?>
        </p>

        <div class="container">

            <form method="post" action="./process/punch-insert.php" style="width: 100%;" id="punch-form">

                <input name="newDepartment" type="hidden">
                <input name='currentDep' type='hidden' value='<?php echo $currentlyClockedDep ?>'>


                <?php

                echo "<div class='button-container'>";
                if ($currentlyClockedDep !== '') {

                    echo "
                    <button class='out-button'  onclick='assignDepartment(this)' data-department='{$currentlyClockedDep}' title='Punch Out'>
    
                        Punch out
    
                    </button>";
                    unset($departments[$currentDepIndex]);
                }

                echo '<div class="inner-btn-container">';

                foreach ($departments as $department) {

                    $class = ($isClocked) ? "out-button" : "in-button";
                    $innerText = ($isClocked) ? "Punch Out" : "Punch In";

                    if (count($_SESSION['employee']['department']) > 1) {
                        $innerText = $department;
                    }


                    $title = "Change to $department";

                    echo "

                        <button class='in-button'  onclick='assignDepartment(this)' data-department='{$department}' title='{$title}'>

                            $innerText

                        </button>";
                }
                echo "
                    </div>
                </div>";
                ?>

                <p>

                <div class="datetime-container">
                    <?php echo $prettyTime ?>
                </div>
                <button onclick="window.location='index.php'" type="button" class="cancel p4">Cancel/No Save</button>

            </form>

        </div>
        <script>
            function assignDepartment(departmentButton) {

                let newDepartment = document.querySelector('form>input[name="newDepartment"]')
                let currentDep = document.querySelector('form>input[name="currentDep"]')

                if (departmentButton.getAttribute('data-department') === currentDep.value) return;

                newDepartment.value = departmentButton.getAttribute('data-department');
            }
        </script>

</html>