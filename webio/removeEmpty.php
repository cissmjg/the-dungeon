<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
const REMOVE_EMPTY = 'removeEmpty';

function getRemoveEmpty(&$errors, &$input) {
    getOptionalBooleanParameter($errors, $input, __FILE__, REMOVE_EMPTY, false);
}

?>