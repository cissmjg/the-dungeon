<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);
require_once __DIR__ . '/../helper/RestHeaderHelper.php';

require_once __DIR__ . '/../classes/skillCatalog.php';

$skill_catalog = new SkillCatalog();
$skill_catalog->init($pdo, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($skill_catalog);
}

?>