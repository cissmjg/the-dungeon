<?php

require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
require_once __DIR__ . '/../dbio/constants/missileRanges.php';

require_once __DIR__ . '/rollModifier/rmUIContainer.php';
require_once __DIR__ . '/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../fa/faChevronIcon.php';
require_once __DIR__ . '/../fa/faChevronIndentedIcon.php';

require_once 'playerCharacterWeapon.php';
require_once 'playerCharacterSkillSet.php';
require_once 'rowClassManager.php';

abstract class PlayerCharacterWeaponRenderer {

    protected $combat_mode = COMBAT_MODE_UNMOUNTED;
    public function getCombatMode() {
        return $this->combat_mode;
    }

    public function setCombatMode($combat_mode) {
        $this->combat_mode = $combat_mode;
    }

    protected $ready_weapon_style = 'rmWeaponContainerIsReadyBackground';
    public function getReadyWeaponStyle() {
        return $this->ready_weapon_style;
    }

    public function setReadyWeaponStyle($ready_weapon_style) {
        $this->ready_weapon_style = $ready_weapon_style;
    }

    protected $player_character_weapon;
    public function getPlayerCharacterWeapon() {
        return $this->player_character_weapon;
    }

    protected $player_character_skill_set;
    public function getPlayerCharacterSkillSet() {
        return $this->player_character_skill_set;
    }

    protected $character_details;
    public function getCharacterDetails() {
        return $this->character_details;
    }

    protected $attribute_metadata;
    public function getAttributeMetadata() {
        return $this->attribute_metadata;
    }

    protected $weapon_container_style = 'rmWeaponContainer';
    public function getWeaponContainerStyle() {
        return $this->weapon_container_style;
    }

    protected $weapon_container_background_style = '';
    public function getWeaponContainerBackgroundStyle() {
        return $this->weapon_container_background_style;
    }

    public function setWeaponContainerBackgroundStyle($weapon_container_background_style) {
        $this->weapon_container_background_style = $weapon_container_background_style;
    }

    public function formatCellStyle($use_previous_class) {
        $cell_style = $this->weapon_container_style;

        $cell_style .= ' ' . $this->getRowClassManager()->getClassName();
        return $cell_style;
    }

    protected $row_class_manager;
    public function getRowClassManager() {
        return $this->row_class_manager;
    }

    public function __construct(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager) {
        $this->player_character_weapon = $player_character_weapon;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->row_class_manager = $row_class_manager;
    }

    abstract function render();

    abstract function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, MissileRange $missile_range);

    abstract function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details);

    protected function buildRmWeaponPanel(RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, $weapon_panel_name) {

        if ($to_hit_calculator->getRmCollection()->empty() && $damage_calculator->getRmCollection()->empty()) {
            return '';
        }

        $output_html  = HtmlHelper::buildDivStartTagWithId('', $weapon_panel_name, true) . PHP_EOL;
        if (!$to_hit_calculator->getRmCollection()->empty()) {
            $output_html .= $this->buildUIHitRmCollection($to_hit_calculator);
            if (!$damage_calculator->getRmCollection()->empty()) {
                $output_html .= HtmlHelper::buildDivTag('', '&nbsp;');
            }
        }

        if (!$damage_calculator->getRmCollection()->empty()) {
            $output_html .= $this->buildUIDamageRmCollection($damage_calculator);
        }
        
        $output_html .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $output_html;
    }

    protected function calculateHitAdj(RmCollectionCalculator $to_hit_calculator) {

        return sprintf("%+d", $to_hit_calculator->aggregate());
    }

    protected function calculateDmgAdj(RmCollectionCalculator $damage_calculator) {

        return sprintf("%+d", $damage_calculator->aggregate());
    }

    protected function buildUIHitRmCollection(RmCollectionCalculator $to_hit_calculator) {
        $rm_ui_hit_container = new RmUIContainer($to_hit_calculator->getRmCollection(), 'To Hit');
        return $rm_ui_hit_container->render();
    }

    protected function buildUIDamageRmCollection(RmCollectionCalculator $damage_calculator) {
        $rm_ui_dmg_container = new RmUIContainer($damage_calculator->getRmCollection(), 'Damage');
        return $rm_ui_dmg_container->render();
    }

    protected function buildRmChevronClickIcon($rm_panel_id, $rm_panel_icon_id, $rm_icon_id) {
        $chevron_icon = new FaChevronIcon();
        $this->decorateChevron($chevron_icon, $rm_panel_id, $rm_panel_icon_id, $rm_icon_id);

        return $chevron_icon->build();
    }

    protected function buildRmChevronIndentedClickIcon($rm_panel_id, $rm_panel_icon_id, $rm_icon_id) {
        $chevron_icon = new FaChevronIndentedIcon();
        $this->decorateChevron($chevron_icon, $rm_panel_id, $rm_panel_icon_id, $rm_icon_id);

        return $chevron_icon->build();
    }
    private function decorateChevron(FaActionIcon $chevron_icon, $rm_panel_id, $rm_panel_icon_id, $rm_icon_id) {
        $chevron_icon->setOnClickJsFunction('rmChevronClick');
        $chevron_icon->addOnclickJsParameter($rm_panel_id);
        $chevron_icon->addOnclickJsParameter($rm_panel_icon_id);
        $chevron_icon->addUnquotedOnclickJsParameter('DEFAULT_CLOSED_ICON_CLASS');	// Javascript constant NOT PHP constant
        $chevron_icon->addUnquotedOnclickJsParameter('DEFAULT_OPEN_ICON_CLASS');	// Javascript constant NOT PHP constant
        $chevron_icon->setIconId($rm_icon_id);

        return $chevron_icon;
    }

    protected function isPreferred(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details) {
        if (!$character_details->isCavalierType()) {
            return false;
        }

        $is_long_sword = $player_character_weapon->getWeaponProficiencyId() == LONG_SWORD || $player_character_weapon->getWeaponProficiencyId() == ELVEN_THIN_BLADE;
        $is_short_bow = $player_character_weapon->getWeaponProficiencyId() == SHORT_BOW;
        $is_preferred_cavalier_level = $player_character_skill_set->isWeaponPreferred($player_character_weapon->getWeaponProficiencyId());
        
        return $is_long_sword || $is_short_bow || $is_preferred_cavalier_level;
    }
}
?>