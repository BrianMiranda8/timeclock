<?php
session_start();

include("../functions/validation.php");
validation();
$role = -1;

$managerRole = $_SESSION['manager']['role'];
extract($_SESSION['employee']);


if (isset($_SESSION['employee']['role'])) {
    $role = $_SESSION['employee']['role'];
}

$header = ($employeeid == -1) ? "Add New Employee: " : "Currently Editing: {$fullName}";
$buttonText = ($employeeid == -1) ? "Save Employee" : "Submit Changes";
$roles = array(
    'Employee' => 4,
    "Super User" => 0,
    "Manager" => 1
);

$states = array(
    "Active" => 1,
    "Inactive" => 0
);

$dep1Checked = '';
$dep2Checked = '';
$dep3Checked = '';
$dep4Checked = '';

for ($index = 0; $index < count($department); $index++) {

    switch ($department[$index]) {
        case "Administration":
            $dep1Checked = 'checked ';
            break;
        case "Frame/Paint":
            $dep2Checked = 'checked ';
            break;
        case "Sales":
            $dep3Checked = 'checked ';
            break;
        case  "Service":
            $dep4Checked = 'checked ';
            break;
    }
}




?>

<html>


<?php
include('../header.php');
?>

<body>
    <div class="time-report">
        <br>

        <span style="margin-left: 10px; border-radius: 4px; font-size: 1.2em;color: white;background-color: gray;padding: 4px;"><?php echo $header; ?></span>

        <form method="POST" id="form" action="../process/manager-update-employee.php" style="margin-top: 10px;">
            <input type='hidden' name='department' value='<?php echo implode(',', $department); ?>' id='department-input'>
            <table style="margin: auto;width: 99%;">
                <tr>
                    <td>

                        <label for="fname">First Name:</label>
                        <br /><input type="text" style="width: 200;" onchange="enableSave();" required name="fname" id="fname" value="<?php echo $fname; ?>">
                    </td>
                    <td>

                        <label for="lname">Last Name:</label>
                        <br />
                        <input onchange="enableSave();" style="width: 200;" type="text" required name="lname" id="lname" value="<?php echo $lname; ?>">

                    </td>
                    <td style="width: 33%;">
                        <label for="list_name">Nick Name for List</label>
                        <br />
                        <input type="text" onchange="enableSave();" name="list_name" id="list_name" value="<?php echo $list_name; ?>">

                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="role" title="0 is admin, 4 is employee">Role:</label>
                        <br />

                        <select onchange="enableSave();" required name="role" id="role" type="text" class="edit-dropdown" style="width: 135px;text-align:center;">

                            <?php
                            if ($employeeid == "-1") {

                                echo "<option  selected disabled>Select Role</option>";
                            }
                            foreach ($roles as $key => $value) {
                                $roleSelected = ($value == $role) ? 'selected' : '';

                                echo "<option value='{$value}' {$roleSelected}>
                                {$key}
                                </option>";
                            }

                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="password">Password:</label>
                        <br />
                        <input onchange="enableSave()" name="password" required id="password" type="password" value="<?php echo $password; ?>">
                    </td>
                    <td>

                        <label for="active">Active?:</label>
                        <br />
                        <select required name="active" id="active" type="text" class="edit-dropdown" onchange="enableSave()" style="width: 120px;text-align:center;">
                            <?php
                            foreach ($states as $key => $value) {
                                $stateSelected = ($value == $active) ? 'selected' : '';

                                echo "<option value='{$value}' {$stateselected}>
                                {$key}
                                </option>";
                            }
                            ?>
                        </select>

                    </td>

                <tr>
                    <td colspan='3'>
                        <fieldset style='display: flex;justify-content: space-around;' required>
                            <legend>Select Department(s) </legend>
                            <input type="checkbox" value="Administration" style="width:fit-content;" data-name="department" <?php echo $dep1Checked; ?>onclick="enableSave();setDepartmentInput();">
                            <label>Administration</label>
                            <input type="checkbox" value="Frame/Paint" style="width:fit-content;" data-name="department" <?php echo $dep2Checked; ?>onclick="enableSave();setDepartmentInput();">
                            <label>Frame/Paint</label>
                            <input id="Dep3" type="checkbox" value="Sales" style="width:fit-content;" data-name="department" <?php echo $dep3Checked; ?> onclick="enableSave();setDepartmentInput();">
                            <label>Sales</label>
                            <input id="Dep4" type="checkbox" value="Service" style="width:fit-content;" data-name="department" <?php echo $dep4Checked; ?> onclick="enableSave();setDepartmentInput();">
                            <label>Service</label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <?php if ($managerRole < 1) { ?>
                        <td colspan='3'>
                            <fieldset>
                                <legend>Pay Rates</legend>
                                <div style="display:flex;align-items:center;width: 100%;justify-content: space-between;">
                                    <span>
                                        <label for="hourly_rate">Hourly</label><br />
                                        <input style="width: 100px;text-align: center;" onchange="enableSave();" required step="any" type="number" name="hourly_rate" value="<?php echo $hourly_rate; ?>">
                                    </span>
                                    <span>
                                        <label for="labor_commission_rate">Labor Rate</label><br />
                                        <input style="width: 100px;text-align: center;" onchange="enableSave();" required step="any" type="number" name="labor_commission_rate" value="<?php echo $labor_commission_rate; ?>">
                                    </span>
                                    <span>
                                        <label for="sales_commission_rate">Sales Rate</label><br />
                                        <input style="width: 100px;text-align: center;" onchange="enableSave();" required step="any" type="number" name="sales_commission_rate" value="<?php echo $sales_commission_rate; ?>">
                                    </span>
                                    <span>
                                        <label for="fit_commission_rate">Fit Rate</label><br />
                                        <input style="width: 100px;text-align: center;" onchange="enableSave();" required step="any" type="number" name="fit_commission_rate" value="<?php echo $fit_commission_rate; ?>">
                                    </span>
                                </div>
                            </fieldset>
                        </td>
                </tr>
            <?php } ?>
            </tr>
            <tr class="center">
                <td colspan="3" style="padding-top: 8px;">
                    <div style="display: flex;align-items: center;justify-content: space-around;">
                        <button id="done-button" type="button" onclick="window.location = '/timeclock/manager-views/manager-employee-search.php'" class="button">
                            <-- Done </button>


                                <button class="button" type="submit" id="submit-button" disabled>
                                    <?php echo $buttonText; ?>
                                </button>

                    </div>
                </td>
            </tr>
            </table>

        </form>

    </div>
    </div>

</body>

</html>

<script>
    function enableSave() {
        document.getElementById('submit-button').disabled = false;
        document.getElementById('done-button').classList.add('cancel')
        document.getElementById('done-button').title = "Changes will be lost"
        document.getElementById('done-button').innerText = 'Cancel/No Save'
    }

    function setDepartmentInput() {

        let hiddenInput = document.querySelector('#department-input');
        let departmentInputs = document.querySelectorAll('input[data-name="department"]');

        let departments = Array.from(departmentInputs)
            .filter(input => input.checked)
            .map(input => input.value)
            .join(',');

        hiddenInput.value = departments;

    }
</script>