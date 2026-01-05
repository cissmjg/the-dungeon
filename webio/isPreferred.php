<?php
require_once __DIR__ . '/requiredParameter.php';
const IS_PREFERRED = 'isPreferred';

function getIsPreferred(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, IS_PREFERRED);
}