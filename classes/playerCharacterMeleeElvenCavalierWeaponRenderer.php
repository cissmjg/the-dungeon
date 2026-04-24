<?php
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';

class PlayerCharacterMeleeElvenCavalierWeaponRenderer {

    private $ready_weapon_style = 'rmWeaponContainerIsReadyBackground';
    public function getReadyWeaponStyle() {
        return $this->ready_weapon_style;
    }

    private $player_character_weapon;
    public function getPlayerCharacterWeapon() {
        return $this->player_character_weapon;
    }

    private $player_character_skill_set;
    public function getPlayerCharacterSkillSet() {
        return $this->player_character_skill_set;
    }

    private $character_details;
    public function getCharacterDetails() {
        return $this->character_details;
    }

    private $attribute_metadata;
    public function getAttributeMetadata() {
        return $this->attribute_metadata;
    }

    private $combat_mode;
    public function getCombatMode() {
        return $this->combat_mode;
    }

    public function setCombatMode($combat_mode) {
        $this->combat_mode = $combat_mode;
    }

    private $weapon_container_style = 'rmWeaponContainer';
    public function getWeaponContainerStyle() {
        return $this->weapon_container_style;
    }

    private $weapon_container_background_style = '';
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

    public function render() {
        if (!$this->player_character_weapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
            return '';
        }

        $melee_to_hit_calculator = new MeleeElvenCavalierToHitRmCollectionCalculator();
        $melee_to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $melee_rm_dmg_calculator = new MeleeElvenCavalierDamageRmCollectionCalculator();
        $melee_rm_dmg_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $attacks_per_round = $this->calculateAttacksPerRound($this->player_character_skill_set, $this->player_character_weapon, $this->character_details);

        $weapon_panel_name = 'weapon-' . $this->player_character_weapon->getWeaponId();
		$weapon_panel_icon_name = 'weapon-icon-' . $this->player_character_weapon->getWeaponId();
        
        $weapon_panel  = $this->buildWeaponDetailEntry($this->player_character_weapon, $melee_to_hit_calculator, $melee_rm_dmg_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        $weapon_panel .= $this->buildRmWeaponPanel($melee_to_hit_calculator, $melee_rm_dmg_calculator, $weapon_panel_name);

        return $weapon_panel;
    }

    function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, MeleeToHitRmCollectionCalculator $melee_to_hit_calculator, MeleeDamageRmCollectionCalculator $melee_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name) {

        $hit_adj = $this->calculateHitAdj($melee_to_hit_calculator);
        $dmg_adj = $this->calculateDmgAdj($melee_damage_calculator);
        $hit_dmg_adj = $hit_adj . '/' . $dmg_adj;

        $weapon_desc = $this->buildRmChevronClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $player_character_weapon->getWeaponDescription();

        $weapon_detail_entry = HtmlHelper::buildDivStartTag($this->formatCellStyle());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailLeft', $weapon_desc);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMeleeWeaponSpeed());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $attacks_per_round);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMeleeWeaponDamage());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $hit_dmg_adj);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $weapon_detail_entry;
    }

    function calculateHitAdj(MeleeToHitRmCollectionCalculator $melee_to_hit_calculator) {

        return sprintf("%+d", $melee_to_hit_calculator->aggregate());
    }

    function calculateDmgAdj(MeleeDamageRmCollectionCalculator $melee_damage_calculator) {

        return sprintf("%+d", $melee_damage_calculator->aggregate());
    }

    function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details) {
        $attacks_per_round = ATTACKS_PER_ROUND_1_FOR_1;
        $primary_class = $character_details->getPrimaryClass();

        $is_preferred = $player_character_weapon->getWeaponProficiencyId() == LONG_SWORD || $player_character_skill_set->isWeaponPreferred($player_character_weapon->getWeaponProficiencyId());
        $attacks_per_round = getAttacksPerRound($primary_class->getClassId(), $primary_class->getClassLevel(), $is_preferred, $this->combat_mode == COMBAT_MODE_MOUNTED);

        return getAttacksPerRoundDescription($attacks_per_round);
    }

    function buildRmWeaponPanel(MeleeToHitRmCollectionCalculator $melee_to_hit_calculator, MeleeDamageRmCollectionCalculator $melee_damage_calculator, $weapon_panel_name) {

	$output_html  = HtmlHelper::buildDivStartTagWithId('', $weapon_panel_name, true) . PHP_EOL;
	$output_html .= $this->buildUIHitRmCollection($melee_to_hit_calculator);
	$output_html .= HtmlHelper::buildDivTag('', '&nbsp;');
	$output_html .= $this->buildUIDamageRmCollection($melee_damage_calculator);
	$output_html .= HtmlHelper::buildDivEndTag() . PHP_EOL;

	return $output_html;
}

    function buildUIHitRmCollection(MeleeToHitRmCollectionCalculator $melee_to_hit_calculator) {
        $rm_ui_hit_container = new RmUIContainer($melee_to_hit_calculator->getWeaponCollection(), 'To Hit');
        return $rm_ui_hit_container->render();
    }

    function buildUIDamageRmCollection(MeleeDamageRmCollectionCalculator $melee_damage_calculator) {
        $rm_ui_dmg_container = new RmUIContainer($melee_damage_calculator->getWeaponCollection(), 'Damage');
        return $rm_ui_dmg_container->render();
    }

    function buildRmChevronClickIcon($rm_panel_id, $rm_panel_icon_id, $rm_icon_id) {
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