<?php
require_once __DIR__ . '/requiredParameter.php';
const DISPLAY_COUNT = 'displayCount';

function getDisplayCount(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, DISPLAY_COUNT);
}