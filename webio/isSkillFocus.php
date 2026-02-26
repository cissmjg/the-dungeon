<?php
require_once __DIR__ . '/requiredParameter.php';
const IS_SKILL_FOCUS = 'isSkillFocus';

function getIsSkillFocus(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, IS_SKILL_FOCUS);
}