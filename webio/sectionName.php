<?php
require_once __DIR__ . '/requiredParameter.php';

const SECTION_NAME = 'sectionName';

function getSectionName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, SECTION_NAME);
}