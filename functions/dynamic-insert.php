<?php

function insertDynamicFields($data)
{
    $keys = array_keys($data);
    $values = array_values($data);

    for ($index = 0; $index < count($values); $index++) {
        $element = $values[$index];
        if ($element === null) {
            $values[$index] = '';
        }
    }

    $paramTypes = '';

    $keysToString = implode(', ', $keys);


    $valuesToString = implode(', ', array_fill(0, count($keys), "?"));

    $query = <<<EOL
            ($keysToString) VALUES
            ($valuesToString)
            EOL;


    $paramTypes = '';

    foreach (array_values($data) as $param) {
        if (is_int($param)) {
            $paramTypes .= 'i'; // Integer type
        } elseif (is_float($param)) {
            $paramTypes .= 'd'; // Double/Float type
        } else {
            $paramTypes .= 's'; // String type (default)
        }
    }




    return array('query' => $query, "types" => $paramTypes, 'values' => $values);
}
