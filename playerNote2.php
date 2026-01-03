<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const PLAYER_NOTE2 = 'playerNote2';

function getPlayerNote2(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, PLAYER_NOTE2, $default_value);
}