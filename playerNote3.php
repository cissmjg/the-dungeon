<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getPlayerNote3(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'playerNote3', $default_value);
}