<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';

require_once __DIR__ . '/classes/attributeMetadata.php';
require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/classes/combatSummaryRendererUserDefined.php';
require_once __DIR__ . '/classes/combatSummaryRendererEditWeaponOrder.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/classes/playerCharacterWeaponSet.php';
require_once __DIR__ . '/classes/twoWeaponFightingConfigurationSet.php';

getPlayerName($errors, $input);
getCharacterName($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];

$character_details = new CharacterDetails();
$character_details->init($pdo, $player_name, $character_name, $errors);

$attribute_metadata = new AttributeMetadata($character_details);

$character_summary_renderer = new CharacterSummaryRenderer($character_name);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$player_character_weapon_set = New PlayerCharacterWeaponSet();
$player_character_weapon_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $player_character_skill_set, $errors);

$two_weapon_fighting_configuration_set = new TwoWeaponFightingConfigurationSet();
$two_weapon_fighting_configuration_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$combat_summary_renderer_edit_weapon_order = new CombatSummaryRendererEditWeaponOrder($player_character_weapon_set, $player_character_skill_set, $character_details, $attribute_metadata, $two_weapon_fighting_configuration_set);
$combat_summary_renderer_edit_weapon_order->init($pdo, $player_name, $character_name, $errors);

$action_bar = buildActionBar($player_name, $character_name, $character_details);

$nf = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

$page_title = $input[CHARACTER_NAME] . ' weapon order';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editCombatSummary.js';
$page_specific_css = 'editCombatSummary.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
<span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span>
<?php
        $combat_summary_renderer_edit_weapon_order->render();
?>
</body>
</html>

<?php
function buildActionBar($player_name, $character_name, \CharacterDetails $character_details) {
    $output_html = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
	$output_html .= '&nbsp;';

    return $output_html;
}
?>