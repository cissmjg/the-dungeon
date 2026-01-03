<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const PLAYER_NOTE3 = 'playerNote3';

function getPlayerNote3(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, PLAYER_NOTE3, $default_value);
}