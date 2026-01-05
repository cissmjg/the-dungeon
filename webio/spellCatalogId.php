<?php
require_once __DIR__ . '/requiredParameter.php';
const SPELL_CATALOG_ID = 'spellCatalogId';

function getSpellCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, SPELL_CATALOG_ID);
}