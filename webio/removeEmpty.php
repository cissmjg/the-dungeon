<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
const CAST_STATUS = 'removeEmpty';

function getRemoveEmpty(&$errors, &$input) {
    getOptionalBooleanParameter($errors, $input, __FILE__, REMOVE_EMPTY, false);
}

?>