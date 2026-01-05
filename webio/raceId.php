<?php
require_once __DIR__ . '/requiredParameter.php';
const RACE_ID = 'race_id';

function getRaceId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, RACE_ID);
}