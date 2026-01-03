<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';
require_once 'characterWeaponTalents.php';
require_once 'characterWeaponTalent.php';

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$weapon_talents= getWeaponTalents($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);
$available_weapon_talents = getWeaponTalentsAvailableForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$add_weapon_url = CurlHelper::buildUrl('characterActionRouter');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $input[CHARACTER_NAME] ?> Weapon Talents</title>
	<link rel="stylesheet" href="dnd-default.css">
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
</head>
<body>
<span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?>
<table>
	<tr><th colspan="7">Trained Weapons</th></tr>
	<tr><th></th><th>Name</th><th>Speed</th><th>Range</th><th>Damage</th></tr>
	<?php

		foreach($weapon_talents AS $weapon_talent) {
			$delete_weapon_icon = buildDeleteWeaponTalentIcon($input[PLAYER_NAME], $input[CHARACTER_NAME], $weapon_talent->getPlayerWeaponId());
			$weapon_proficiency_display = '<tr>';
			$weapon_proficiency_display .= '<td style="text-align: center;">' . $delete_weapon_icon . '</td>';
			$preferred_text = $weapon_talent->isPreferred() ? ' (preferred)' : '';
			$weapon_proficiency_display .= '<td style="text-align: center;">' . $weapon_talent->getWeaponName() . $preferred_text . '</td>';
			$weapon_proficiency_display .= '<td style="text-align: center;">' . $weapon_talent->getWeaponSpeed() . '</td>';
			$weapon_proficiency_display .= '<td style="text-align: center;">' . $weapon_talent->getWeaponRange() . '</td>';
			$weapon_proficiency_display .= '<td style="text-align: center;">' . $weapon_talent->getWeaponDamage() . '</td>';
			$weapon_proficiency_display .= '</tr>' . PHP_EOL;

			echo $weapon_proficiency_display;
			if ($weapon_talent->getWeaponNotes() != NULL) {
				echo '<tr><td colspan="5" style="color: dodgerblue;">' . $weapon_talent->getWeaponNotes() . '</td></tr>';
			}
		}
	?>
</table>
<hr/>
<h3>Available weapons for <?= $input[CHARACTER_NAME] ?></h3>
<form id="addWeapon" name="addWeapon" method="POST" action="<?= $add_weapon_url ?>">
	<input type="hidden" id="playerName" name="playerName" value="<?= $input[PLAYER_NAME]?>">
	<input type="hidden" id="characterName" name="characterName" value="<?= $input[CHARACTER_NAME]?>">
	<input type="hidden" id="characterAction" name="characterAction" value="addWeaponTalent">
	<?php if(isCharacterCavalier($character_summary)): ?>
	<label for="isPreferred"><input type="checkbox" id="isPreferred" name="isPreferred" value="preferred">Is this a preferred weapon?</label>
	<?php else: ?>
	<input type="hidden" id="isPreferred" name="isPreferred" value="not preferred">
	<?php endif ?>
	<br/>

	<!-- On select ... get weapon details and show preview -->
	<select class="valid" id="weaponProficiencyId" name="weaponProficiencyId">
		<?php
			foreach($available_weapon_talents AS $available_weapon_talent) {
				echo '<option value="' . $available_weapon_talent['weapon_id'] . '">' . $available_weapon_talent['weapon_name'] . '</option>' . PHP_EOL;
			}
		?>
	</select>
	<br/><br/>
	<button type="submit">Add Weapon Talent</button>
</form>
</body>
</html>

<?php

function getWeaponTalents(\PDO $pdo, $player_name, $character_name) {

	$weapon_talents = new CharacterWeaponTalents();
	$weapon_talents->init($pdo, $player_name, $character_name);

	return $weapon_talents->getWeaponTalents();
}

function getWeaponTalentsAvailableForPlayerCharacter(\PDO $pdo, $player_name, $character_name) {
    $sql_exec = "CALL getWeaponTalentsAvailableForPlayerCharacter(:playerName, :characterName)";
            
    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);

    try {
        $statement->execute();
    } catch(Exception $e) {
        $errors[] = "Exception in CharacterWeapons.getWeapons : " . $e->getMessage();
    }

    return $statement->fetchAll(PDO::FETCH_ASSOC);

}

function buildActionBar($player_name, $character_name) {
    return ActionBarHelper::buildUserEditIcon($player_name, $character_name);
}

function buildDeleteWeaponTalentIcon($player_name, $character_name, $player_weapon_talent_id) {
	$output_html = '';
	$title = 'Delete Weapon Talent for ' . $character_name;
	$url = buildDeleteWeaponTalentUrl($player_name, $character_name, $player_weapon_talent_id);
	$delete_weapon_icon = '<span class="fa-solid fa-trash" style="cursor: pointer; color: red;" title="' . $title . '"></span>';
	$output_html .= '<a href="' . $url . '">' . $delete_weapon_icon . '</a>';

	return $output_html;
}

function buildDeleteWeaponTalentUrl($player_name, $character_name, $player_weapon_talent_id) {
	$url = CurlHelper::buildUrl('characterActionRouter');
	$url = CurlHelper::addParameter($url, 'characterAction', 'deleteWeaponTalent');
	$url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
	$url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
	$url = CurlHelper::addParameter($url, 'weaponProficiencyId', $player_weapon_talent_id);

	return $url;
}

function isCharacterCavalier($character_summary) {
	$character_classes = $character_summary->getCharacterClasses();
	foreach($character_classes AS $character_class) {
		if($character_class['class_name'] == 'Cavalier' || $character_class['class_name'] == 'Paladin') {
			return true;
		}
	}

	return false;
}
?>
