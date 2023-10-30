<?php
session_start();
include('./functions/validation.php');
include("./functions/employee-datalist.php");

invalidation();

require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$database = 'timeclock';


$timeclock = new mysqli($server, $username, $password, $database);


?>


<!DOCTYPE html>
<html lang="en">

<?php
include('./header.php');
?>


<body onload="window.resizeTo(385, 390);">

    <div class="entry-div">

        <form name="name" id="name" method="POST" action="./controller.php">

            <select id="employee-list" class="select-employee" name="employeeid" autofocus required>
                <option selected disabled></option>
                <?php

                buildEmployeeList($timeclock);
                ?>
            </select>

            <input type="hidden" name="area" id="viewType">

            <p>

                <button onclick="setArea(this)" class="button p1" id="punch_view" value="Time Clock" type="submit">

                    Time Clock

                </button>

            <p>

                <button onclick="setArea(this)" class="button p2" value="View My Time" id="date_find" type="submit">

                    View My Time

                </button>

            <p>

                <button class="button p3 hidden" id="manager_login" type="submit" value="Manager Area" onclick="setArea(this)">

                    Manager Area

                </button>
        </form>

        <button class="button p4 quitButton" onclick="window.close();">

            Quit

        </button>

    </div>
    <script>
        function setArea(button) {
            const hiddenInput = document.getElementById("viewType");
            hiddenInput.value = button.value;
        }

        const areaButtons = document.querySelectorAll('button');
    </script>
</body>
<script>
    (function() {

        const urlParams = new URLSearchParams(window.location.search);


        if (urlParams.has("message")) {

            const message = urlParams.get("message");

            urlParams.delete('message')

            alert(message);
            window.location.search = urlParams.toString();
        }
    })();
    document.getElementById("employee-list").addEventListener('change', (event) => {

        let role = event.target.selectedOptions[0].getAttribute("role");
        managerButton = document.getElementById("manager_login");
        if (role < "2") {

            managerButton = document.getElementById("manager_login");
            managerButton.classList.remove("hidden");
        } else {
            managerButton.classList.add("hidden");
        }

    })
</script>

</html>