<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getPlayerNote1(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'playerNote1', $default_value);
}