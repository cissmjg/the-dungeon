<?php
require_once __DIR__ . '/requiredParameter.php';
const SKILL_CATALOG_ID = 'skillCatalogId';

function getSkillCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SKILL_CATALOG_ID);
}