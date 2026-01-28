<?php
require_once __DIR__ . '/requiredParameter.php';
const HOURS_OF_SLEEP = 'hoursOfSleep';

function getHoursOfSleep(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, HOURS_OF_SLEEP);
}