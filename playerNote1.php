<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const PLAYER_NOTE1 = 'playerNote1';

function getPlayerNote1(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, PLAYER_NOTE1, $default_value);
}