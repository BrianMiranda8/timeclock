<?php

function buildTimeTable(array $punches, array $totaltime, string $department)
{


    echo "<hr>
    <span style='font-size: 1.3em; color: blue;margin-left: 10px;''>$department </span>
    <table class='report-table'>
        <tr>
            <th>IN</th>
            <th>OUT</th>
            <th colspan='2'>TIME</th>
        </tr>
    ";

    foreach ($punches as $time) {

        echo "
        <tr>
            <td>
            {$time["time_in"]}
            </td>
        ";
        if ($time["time_out"] > 0) {
            echo "<td>{$time["time_out"]}</td>";
        } else {
            echo "<td>--</td>";
        }


        if ($time["time_out"] > 0) {
            echo "<td>{$time["total_minutes"]}</td>";
            echo "<td style='color: gray;'> {$time["total_time"]}</td>";
        } else {
            echo "<td>--</td>";
            echo "<td style='color: gray;'>--</td>";
        }
        echo "</tr>";

        echo "<tr>";
    }



    echo "<td colspan='4' style='text-align: right;color: white;font-size: 1em;font-weight: bold;background-color: blue'>";
    echo "Hours Worked:  ";

    if ($totaltime["total_minutes"] != '838:59') {

        echo $totaltime[0]["total_minutes"] . ' - ';
    }


    echo "<font color='lightgray'>";



    echo $totaltime[0]["total_time"];

    echo "</font>";



    echo "</td>
    </tr>
    </table>";
}
