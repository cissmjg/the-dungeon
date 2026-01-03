<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getPlayerNote2(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'playerNote2', $default_value);
}