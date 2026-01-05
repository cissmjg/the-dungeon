<?php
require_once __DIR__ . '/requiredParameter.php';
const IS_READY = IS_READY;

function getIsReady(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, IS_READY);
}