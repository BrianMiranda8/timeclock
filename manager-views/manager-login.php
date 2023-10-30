<?php
session_start();
include("../functions/validation.php");

?>
<html>

<?php
include("../header.php");

?>
<html>

<body>
    <div class="manager-login">

        <p class="center" style="font-size:1.1em;">
            <?php
            echo $_SESSION['employee']['fullName'];
            ?>
        </p>

        <form method="POST" style="width: 70%;margin: auto;" action="./manager-report-search.php">

            <label for=" password">Password:</label>



            <input type="password" name="password" id="password" autofocus>



            <button class="button p2" type="submit">Reports</button>

        </form>

        <form method="POST" action="./manager-employee-search.php">

            <p>

                <button type="submit" class="button p3">Add/Edit Employees</button>

        </form>


        <p>

            <button onclick="window.location = '/timeclock/index.php';" type="button" class="cancel p4">Cancel</button>
    </div>
</body>
<script>
    const passwordInput = document.querySelector("#password");

    document.addEventListener('submit', async (event) => {
        event.preventDefault();
        let action = event.target.getAttribute('action');
        const password = passwordInput.value;
        if (password === "") {
            window.resizeTo(535, 355);
            alert('Password Required');
            return
        };

        const request = await fetch("../process/password-validation.php", {
            method: "POST",
            body: password
        });
        const response = await request.json();

        if (response.validation) {
            window.location = `${action}`;
            return;
        }
        window.resizeTo(535, 355);
        alert(response.message);



    })
</script>

</html>