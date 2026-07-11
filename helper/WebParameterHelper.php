<?php

const OPTIONAL_STRING_PARAMETER = '-';
const OPTIONAL_INTEGER_PARAMETER = 0;

require_once __DIR__ . '/../webio/optionalParameter.php';

function extractParametersForPrefix(&$errors, $prefix, $max_number_items) {
    // A local version of $input is created, so that a concise array of JUST the present variables can be returned
    $input = [];
    for($item_index = 1; $item_index <= $max_number_items; $item_index++) {
        $candidate_parameter_name = sprintf("%s%d", $prefix, $item_index);
        getOptionalStringParameter($errors, $input, __FILE__, $candidate_parameter_name, OPTIONAL_STRING_PARAMETER);
        
        // Remove the item if not present
        if ($input[$candidate_parameter_name] == OPTIONAL_STRING_PARAMETER) {
            unset($input[$candidate_parameter_name]);
        }
    }

    return $input;
}

?>