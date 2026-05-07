<?php
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';

require_once __DIR__ . '/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../fa/faChevronIcon.php';

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

    public function formatCellStyle() {
        $cell_style = $this->weapon_container_style;
        if ($this->player_character_weapon->getIsReady()) {
            $cell_style .= ' ' . $this->ready_weapon_style;
        } else if (strlen($this->weapon_container_background_style) > 0) {
            $cell_style .= ' ' . $this->weapon_container_background_style;
        }

        return $cell_style;
    }

    public function __construct(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata) {
        $this->player_character_weapon = $player_character_weapon;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
    }

    abstract function render();

    abstract function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, MeleeToHitRmCollectionCalculator $melee_to_hit_calculator, MeleeDamageRmCollectionCalculator $melee_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);

    abstract function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details);

    protected function buildRmWeaponPanel(RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, $weapon_panel_name) {

        $output_html  = HtmlHelper::buildDivStartTagWithId('', $weapon_panel_name, true) . PHP_EOL;
        $output_html .= $this->buildUIHitRmCollection($to_hit_calculator);
        $output_html .= HtmlHelper::buildDivTag('', '&nbsp;');
        $output_html .= $this->buildUIDamageRmCollection($damage_calculator);
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
        $chevron_icon->setOnClickJsFunction('rmChevronClick');
        $chevron_icon->addOnclickJsParameter($rm_panel_id);
        $chevron_icon->addOnclickJsParameter($rm_panel_icon_id);
        $chevron_icon->addUnquotedOnclickJsParameter('DEFAULT_CLOSED_ICON_CLASS');	// Javascript constant NOT PHP constant
        $chevron_icon->addUnquotedOnclickJsParameter('DEFAULT_OPEN_ICON_CLASS');	// Javascript constant NOT PHP constant
        $chevron_icon->setIconId($rm_icon_id);

        return $chevron_icon->build();
    }
}
?>