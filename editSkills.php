<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'CurlHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'characterAttributes.php';
require_once 'RestHeaderHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$params = [];
$params['playerName'] = $input['playerName'];
$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

$url = CurlHelper::buildUrl('getPlayerCharacterSkills');
$raw_results = CurlHelper::performGetRequest($url, $params);

$character_skills = json_decode($raw_results);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$skill_catalog = getAllSkills($pdo, $errors);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $input[CHARACTER_NAME] ?> Spells</title>
	<link rel="stylesheet" href="dnd-default.css">
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
    <script src="submitTheForm.js" type="text/javascript"></script>
</head>
<body>
<?php
    $action_bar = buildActionBar($input['playerName'], $input[CHARACTER_NAME]);
    echo '<div style="width: 100%;"><span class="character_summary">' . $character_summary_stats . '</span><span class="action_bar">' . $action_bar . '</span></div>' . PHP_EOL;
?>
    <table>
    <tr><th colspan="4">Skills for <?= $input[CHARACTER_NAME] ?></tr>
<?php
    $row_count = 0;
    foreach($character_skills AS $character_skill) {
        $delete_skill_icon = buildDeletDeleteIcon($input['playerName'], $input[CHARACTER_NAME], $character_skill->player_character_skill_id);
        if ($row_count % 2 == 0) {
            if ($row_count > 0) {
                echo '</tr>' . PHP_EOL;
            }
            echo '<tr>';
        }
        echo '<td>' . $delete_skill_icon . '</td>' . '<td>' . $character_skill->formatted_name . '</td>';
        $row_count++;
    }
    if ($row_count %2 != 0) {
        echo '<td>&nbsp;</td>';
    }
    echo '</tr>' . PHP_EOL;
?>
    </table>
<div>
    <pre><?php echo print_r($character_skills, true); ?></pre>
</div>
</body>
</html>
<?php

function getAllSkills(\PDO $pdo, &$errors) {
    $sql_exec = "CALL getAllSkills()";

    $statement = $pdo->prepare($sql_exec);

    try {
        $statement->execute();
    } catch(Exception $e) {
        $errors[] = "Exception in getAllSkills : " . $e->getMessage();
    }    

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function buildDeletDeleteIcon($player_name, $character_name, $character_skill_id) {
	$output_html = '';
	$title = 'Delete Skill for ' . $character_name;
	$url = buildDeleteSkillUrl($player_name, $character_name, $character_skill_id);
	$delete_skill_icon = '<span class="fa-solid fa-trash" style="cursor: pointer; color: red;" title="' . $title . '"></span>';
	$output_html .= '<a href="' . $url . '">' . $delete_skill_icon . '</a>';

	return $output_html;
}

function buildDeleteSkillUrl($player_name, $character_name, $character_skill_id) {
    $url = CurlHelper::buildUrl('characterActionRouter');
	$url = CurlHelper::addParameter($url, 'characterAction', 'deleteCharacterSkill');
	$url = CurlHelper::addParameter($url, 'playerName', $player_name);
	$url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
	$url = CurlHelper::addParameter($url, 'playerCharacterSkillId', $character_skill_id);

	return $url;
}

function buildActionBar($player_name, $character_name) {
    return ActionBarHelper::buildUserViewIcon($player_name, $character_name) . PHP_EOL;
}

?>