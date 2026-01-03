<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getRaceId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'race_id');
}