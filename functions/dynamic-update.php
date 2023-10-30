<?php

function updateDynamicFields($data, $primaryKey = 'ID')
{

    $count = count(array_keys($data));
    $paramTypes = '';
    $string = '';
    $id = '';
    $values = array();
    for ($index = 0; $index < $count; $index++) {

        $key = array_keys($data)[$index];
        $value = $data[$key];

        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (preg_match("/\b($primaryKey|id)\b/", $key)) {
            $id = $value;
            continue;
        }
        if (is_numeric($value)) {
            if (is_int($value + 0)) {
                $paramTypes .= 'i'; // Integer type
                $value = intval($value);
            } else {
                $paramTypes .= 'd'; // Double/Float type
                $value = floatval($value);
            }
        } else {
            if ($value === null) {
                $value = '';
            }
            $paramTypes .= 's'; // String type (default)
        }


        $string = $string . "`$key` = ?,";


        array_push($values, $value);
    }
    array_push($values, intval($id));
    $paramTypes .= 'i';
    return array('query' => preg_replace('/,(?=[^,]*$)/', '', $string), "types" => $paramTypes, 'values' => $values);
}
