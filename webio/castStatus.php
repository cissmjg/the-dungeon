<?php
require_once __DIR__ . '/requiredParameter.php';
const CAST_STATUS = 'castStatus';

function getCastStatus(&$errors, &$input) {
    getRequiredBooleanParameter($errors, $input, __FILE__, 'castStatus');
}

?>