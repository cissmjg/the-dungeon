<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';
require_once __DIR__ . '/webio/spellLevel.php';
require_once __DIR__ . '/webio/spellSlotId.php';
require_once __DIR__ . '/webio/spellCatalogId.php';
require_once __DIR__ . '/webio/spellDuration.php';
require_once __DIR__ . '/webio/spellCastingTime.php';
require_once __DIR__ . '/webio/removeEmpty.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/characterClassName.php';

require_once __DIR__ . '/classes/rowClassManager.php';
require_once __DIR__ . '/classes/readySpellFormIdLookup.php';
require_once __DIR__ . '/classes/characterSummary.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/classes/playerCharacterPoolSpell.php';
require_once __DIR__ . '/classes/playerCharacterSpellPool.php';
require_once __DIR__ . '/classes/playerCharacterReadySpell.php';
require_once __DIR__ . '/classes/playerCharacterReadySpellSet.php';
require_once __DIR__ . '/classes/playerCharacterReadySpellRenderer.php';

require_once __DIR__ . '/dbio/constants/emptySpellSlot.php';
require_once __DIR__ . '/dbio/constants/cantripSpellSlot.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/dbio/constants/spellTypes.php';
require_once __DIR__ . '/dbio/constants/readySpellState.php';

const CAST_SPELL_SLOT_FORM_ID = 'cast-spell';
const CAST_SPELL_SLOT_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;
const CAST_SPELL_CASTING_TIME_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_CASTING_TIME;
const CAST_SPELL_DURATION_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_DURATION;

const UPDATE_SPELL_SLOT_FORM_ID = 'update-spell-slot';
const UPDATE_SLOT_SPELL_SLOT_ID = UPDATE_SPELL_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;
const UPDATE_SLOT_SPELL_CATALOG_ID = UPDATE_SPELL_SLOT_FORM_ID . '-' . SPELL_CATALOG_ID;
const UPDATE_SLOT_SPELL_CHARACTER_CLASS_NAME = UPDATE_SPELL_SLOT_FORM_ID . CHARACTER_CLASS_NAME;
const UPDATE_SLOT_SPELL_LEVEL = UPDATE_SPELL_SLOT_FORM_ID . SPELL_LEVEL;

const RECLAIM_CANTRIPS_FORM_ID = 'reclaim-cantrips';
const RECLAIM_CANTRIPS_SLOT_ID = RECLAIM_CANTRIPS_FORM_ID . '-' . SPELL_SLOT_ID;

const RESET_SLOT_FORM_ID = 'reset-slot';
const RESET_SLOT_SPELL_SLOT_ID = RESET_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;

const STOP_CASTING_FORM_ID = 'stop-casting';
const STOP_CASTING_SLOT_ID = STOP_CASTING_FORM_ID . '-' . SPELL_SLOT_ID;

const STOP_RUNNING_FORM_ID = 'stop-running';
const STOP_RUNNING_SLOT_ID = STOP_RUNNING_FORM_ID . '-' . SPELL_SLOT_ID;

$ready_spell_form_id_lookup = new ReadySpellFormIdLookup();
$ready_spell_form_id_lookup->setCastSpellSlotFormId(CAST_SPELL_SLOT_FORM_ID);
$ready_spell_form_id_lookup->setCastSpellSlotDuration(CAST_SPELL_DURATION_ID);
$ready_spell_form_id_lookup->setCastSpellSlotCastingTime(CAST_SPELL_CASTING_TIME_ID);
$ready_spell_form_id_lookup->setCastSpellSlotId(CAST_SPELL_SLOT_ID);

$ready_spell_form_id_lookup->setUpdateSpellSlotFormId(UPDATE_SPELL_SLOT_FORM_ID);
$ready_spell_form_id_lookup->setUpdateSpellSlotSpellCatalogId(UPDATE_SLOT_SPELL_CATALOG_ID);
$ready_spell_form_id_lookup->setUpdateSpellSlotId(UPDATE_SLOT_SPELL_SLOT_ID);
$ready_spell_form_id_lookup->setUpdateSpellSlotCharacterClassName(UPDATE_SLOT_SPELL_CHARACTER_CLASS_NAME);
$ready_spell_form_id_lookup->setUpdateSpellSlotSpellLevel(UPDATE_SLOT_SPELL_LEVEL);

$ready_spell_form_id_lookup->setReclaimCantripsFormId(RECLAIM_CANTRIPS_FORM_ID);
$ready_spell_form_id_lookup->setReclaimCantripsSlotId(RECLAIM_CANTRIPS_SLOT_ID);

$ready_spell_form_id_lookup->setResetSlotFormId(RESET_SLOT_FORM_ID);
$ready_spell_form_id_lookup->setResetSlotId(RESET_SLOT_SPELL_SLOT_ID);

$ready_spell_form_id_lookup->setStopCastingSlotFormId(STOP_CASTING_FORM_ID);
$ready_spell_form_id_lookup->setStopCastingSlotId(STOP_CASTING_SLOT_ID);

$ready_spell_form_id_lookup->setStopRunningSlotFormId(STOP_RUNNING_FORM_ID);
$ready_spell_form_id_lookup->setStopRunningSlotId(STOP_RUNNING_SLOT_ID);

// Populate player and character names in $input
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$player_character_spell_pool = new PlayerCharacterSpellPool();
$player_character_spell_pool->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$player_character_ready_spell_set = new PlayerCharacterReadySpellSet();
$player_character_ready_spell_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$locale = 'en_US';
$nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
$action_bar = ActionBarHelper::buildActionBar($input[PLAYER_NAME], $input[CHARACTER_NAME]);

$row_class_manager = new RowClassManager();
$row_class_manager->setDefaultClassName('readySpell');
$row_class_manager->setAlternateClassName('readySpellAlt');

$page_title = $input[CHARACTER_NAME] . ' spells';
$site_css_file = 'dnd-default.css';
$page_specific_js = 'editReadySpells.js';
$page_specific_css = 'editReadySpells.css';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
    <form name="<?= CAST_SPELL_SLOT_FORM_ID ?>" id="<?= CAST_SPELL_SLOT_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_CAST_SPELL_SLOT ?>">
        <input type="hidden" name="<?= SPELL_DURATION ?>" id="<?= CAST_SPELL_DURATION_ID ?>" value="">
        <input type="hidden" name="<?= SPELL_CASTING_TIME ?>" id="<?= CAST_SPELL_CASTING_TIME_ID ?>" value="">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= CAST_SPELL_SLOT_ID ?>" value="">
    </form>
    <form name="<?= UPDATE_SPELL_SLOT_FORM_ID ?>" id="<?= UPDATE_SPELL_SLOT_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_UPDATE_READY_SPELL_SLOT ?>">
        <input type="hidden" name="<?= SPELL_CATALOG_ID ?>" id="<?= UPDATE_SLOT_SPELL_CATALOG_ID ?>" value="">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= UPDATE_SLOT_SPELL_SLOT_ID ?>" value="">
        <input type="hidden" name="<?= CHARACTER_CLASS_NAME ?>" id="<?= UPDATE_SLOT_SPELL_CHARACTER_CLASS_NAME ?>" value="">
        <input type="hidden" name="<?= SPELL_LEVEL ?>" id="<?= UPDATE_SLOT_SPELL_LEVEL ?>" value="">
    </form>
    <form name="<?= RECLAIM_CANTRIPS_FORM_ID ?>" id="<?= RECLAIM_CANTRIPS_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_RECLAIM_CANTRIP_SLOTS ?>">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= RECLAIM_CANTRIPS_SLOT_ID ?>" value="">
    </form>
    <form name="<?= RESET_SLOT_FORM_ID ?>" id="<?= RESET_SLOT_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_RESET_SPELL_SLOT ?>">
        <input type="hidden" name="<?= SPELL_DURATION ?>" id="<?= RESET_SLOT_FORM_ID . '-' . SPELL_DURATION ?>" value="0">
        <input type="hidden" name="<?= SPELL_CASTING_TIME ?>" id="<?= RESET_SLOT_FORM_ID . '-' . SPELL_CASTING_TIME ?>" value="0">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= RESET_SLOT_SPELL_SLOT_ID ?>" value="">
    </form>
    <form name="<?= STOP_CASTING_FORM_ID ?>" id="<?= STOP_CASTING_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_STOP_CASTING_SPELL_SLOT ?>">
        <input type="hidden" name="<?= SPELL_DURATION ?>" id="<?= STOP_CASTING_FORM_ID . '-' . SPELL_DURATION ?>" value="0">
        <input type="hidden" name="<?= SPELL_CASTING_TIME ?>" id="<?= STOP_CASTING_FORM_ID . '-' . SPELL_CASTING_TIME ?>" value="0">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= STOP_CASTING_SLOT_ID ?>" value="">
    </form>
    <form name="<?= STOP_RUNNING_FORM_ID ?>" id="<?= STOP_RUNNING_FORM_ID ?>" method="POST" action="<?= CurlHelper::buildCharacterActionRouterUrl()?>">
        <input type="hidden" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_NAME ?>" id="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>">
        <input type="hidden" name="<?= CHARACTER_ACTION ?>" id="<?= CHARACTER_ACTION ?>" value="<?= CHARACTER_ACTION_STOP_RUNNING_SPELL_SLOT ?>">
        <input type="hidden" name="<?= SPELL_DURATION ?>" id="<?= STOP_RUNNING_FORM_ID . '-' . SPELL_DURATION ?>" value="0">
        <input type="hidden" name="<?= SPELL_CASTING_TIME ?>" id="<?= STOP_RUNNING_FORM_ID . '-' . SPELL_CASTING_TIME ?>" value="0">
        <input type="hidden" name="<?= SPELL_SLOT_ID ?>" id="<?= STOP_RUNNING_SLOT_ID ?>" value="">
    </form>
    <?php
    echo '<div style="width: 100%;"><span class="character_summary">' . $character_summary_stats . '</span><span class="action_bar">' . $action_bar . '</span></div>';
    if ($player_character_spell_pool->isEmpty()) {
        echo '<h3>No spells available</h3>';
    } else {
        echo '<div style="width: 100%; text-align: center; padding-bottom: 3px;"><button onclick="window.location.reload();">End Of Round</button></div>' . PHP_EOL;
        echo '<table class="ready_spells">' . PHP_EOL;
        foreach ($player_character_ready_spell_set->getSpellMap() as $character_class_name => $spellsByClass) {
            foreach($spellsByClass as $spell_type => $spellsByClassType) {

                $spellType_header = buildSpellTypeHeader($spell_type, $character_class_name);
                echo $spellType_header . PHP_EOL;

                foreach($spellsByClassType as $spell_level => $spellsByClassTypeLevel) {
                    $spellLevel_header = buildSpellLevelHeader( $spell_type, $spell_level, $nf, getClassID($character_class_name));
                    echo $spellLevel_header . PHP_EOL;

                    foreach($spellsByClassTypeLevel AS $ready_spell) {
                        $ready_spell_renderer = new PlayerCharacterReadySpellRenderer($ready_spell, $player_character_spell_pool, $ready_spell_form_id_lookup, $row_class_manager);
                        echo $ready_spell_renderer->render();
                    }
                }
            }
        }
        echo '</table>' . PHP_EOL;
        
    }
    ?>
</body>
</html>

<?php

function buildSpellTypeHeader($spellType, $characterClassName) {
    $header = '<tr><th class="spell_type_header" colspan="7">' . $spellType . ' spells' . '<span style="float: right;">(' . $characterClassName . ')</span></th></tr>';
    return $header;
}

function buildSpellLevelHeader($spell_type, $spellLevel, $nf, $character_class_id) {
    $is_archer_mu_spells = isArcherMuSpells($character_class_id, $spell_type);
    $spellLevelDesc = '';
    if ($is_archer_mu_spells) {
        $spellLevelDesc = 'Spells';
    } else if ($spellLevel == 'Cantrip') {
        $spellLevelDesc = 'Cantrips';
    } else {
        $spellLevelDesc = $nf->format($spellLevel) . ' level';
    }

    $header = '<tr><th>' . $spellLevelDesc . '</th><th>Name</th><th>&nbsp;</th><th>CT</th><th>Rng</th><th>Dur</th><th>AoE</th></tr>';
    return $header;
}

function isArcherMuSpells($character_class_id, $spell_type_name) {
    $spell_type_id = getSpellTypeIDFromName($spell_type_name);
    return (isArcherType($character_class_id) && $spell_type_id == SPELL_TYPE_MAGIC_USER);
}

?>
