<?php

require_once __DIR__ . '/../dbio/constants/twoWeaponFightingHand.php';
require_once __DIR__ . '/rollModifier/meleeTwoHandedFightingToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

class TwoWeaponFightingRenderer extends PlayerCharacterWeaponRenderer {

    private $two_weapon_fighting_config;
    public function getTwoWeaponFightingConfig() {
        return $this->two_weapon_fighting_config;
    }

    private $main_hand_weapon;
    public function getMainHandWeapon() {
        return $this->main_hand_weapon;
    }

    private $off_hand_weapon;
    public function getOffHandWeapon() {
        return $this->off_hand_weapon;
    }

    protected $player_character_weapon_set;
    public function getPlayerCharacterWeaponSet() {
        return $this->player_character_weapon_set;
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

    public function __construct(TwoWeaponFightingConfiguration $two_weapon_fighting_config, PlayerCharacterWeaponSet $player_character_weapon_set, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager) {
        $this->two_weapon_fighting_config = $two_weapon_fighting_config;
        $this->player_character_weapon_set = $player_character_weapon_set;

        $main_hand_weapon_id = $this->two_weapon_fighting_config->getWeapon1Id();
        $this->main_hand_weapon = $this->player_character_weapon_set->getWeaponById($main_hand_weapon_id);

        $off_hand_weapon_id = $this->two_weapon_fighting_config->getWeapon2Id();
        $this->off_hand_weapon = $this->player_character_weapon_set->getWeaponById($off_hand_weapon_id);

        parent::__construct($this->main_hand_weapon, $player_character_skill_set, $character_details, $attribute_metadata, $row_class_manager);
    }

    public function render() {
        
        // Main Hand To Hit calculator
        $main_hand_melee_to_hit_calculator = new MeleeTwoWeaponFightingToHitRmCollectionCalculator();
        $main_hand_melee_to_hit_calculator->setTwoWeaponFightingHand(TWO_WEAPON_FIGHTING_MAIN_HAND);
        $main_hand_melee_to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->main_hand_weapon, $this->attribute_metadata);

        // Off Hand To Hit calculator
        $off_hand_melee_to_hit_calculator = new MeleeTwoWeaponFightingToHitRmCollectionCalculator();
        $off_hand_melee_to_hit_calculator->setTwoWeaponFightingHand(TWO_WEAPON_FIGHTING_OFF_HAND);
        $off_hand_melee_to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->off_hand_weapon, $this->attribute_metadata);

        // Main Hand Damage Calculator
        $main_hand_melee_rm_dmg_calculator = new MeleeDamageRmCollectionCalculator();
        $main_hand_melee_rm_dmg_calculator->gather($this->character_details, $this->player_character_skill_set, $this->off_hand_weapon, $this->attribute_metadata);

        // Off Hand Damage Calculator
        $off_hand_melee_rm_dmg_calculator = new MeleeDamageRmCollectionCalculator();
        $off_hand_melee_rm_dmg_calculator->gather($this->character_details, $this->player_character_skill_set, $this->off_hand_weapon, $this->attribute_metadata);

        $attacks_per_round = $this->calculateAttacksPerRound($this->player_character_skill_set, $this->main_hand_weapon, $this->character_details);

        // Main Hand Render
        $weapon_panel_name = 'weapon-' . $this->main_hand_weapon->getWeaponId() . '-2wf';
		$weapon_panel_icon_name = 'weapon-icon-' . $this->main_hand_weapon->getWeaponId() . '-2wf';
        
        $weapon_panel  = $this->buildWeaponDetailEntry($this->main_hand_weapon, $main_hand_melee_to_hit_calculator, $main_hand_melee_rm_dmg_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, TWO_WEAPON_FIGHTING_MAIN_HAND);
        $weapon_panel .= $this->buildRmWeaponPanel($main_hand_melee_to_hit_calculator, $main_hand_melee_rm_dmg_calculator, $weapon_panel_name);

        // Off Hand Render
        $weapon_panel_name = 'weapon-' . $this->off_hand_weapon->getWeaponId() . '-2wf';
		$weapon_panel_icon_name = 'weapon-icon-' . $this->off_hand_weapon->getWeaponId() . '-2wf';

        $weapon_panel .= $this->buildWeaponDetailEntry($this->off_hand_weapon, $off_hand_melee_to_hit_calculator, $off_hand_melee_rm_dmg_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, TWO_WEAPON_FIGHTING_OFF_HAND);
        $weapon_panel .= $this->buildRmWeaponPanel($off_hand_melee_to_hit_calculator, $off_hand_melee_rm_dmg_calculator, $weapon_panel_name);

        return $weapon_panel;
    }
    
    function buildRmWeaponPanel(RmCollectionCalculator $melee_to_hit_calculator, RmCollectionCalculator $melee_damage_calculator, $weapon_panel_name) {

        $output_html  = HtmlHelper::buildDivStartTagWithId('', $weapon_panel_name, true) . PHP_EOL;
        $output_html .= $this->buildUIHitRmCollection($melee_to_hit_calculator);
        $output_html .= HtmlHelper::buildDivTag('', '&nbsp;');
        $output_html .= $this->buildUIDamageRmCollection($melee_damage_calculator);
        $output_html .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $output_html;
    }

    function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details) {
        $attacks_per_round = ATTACKS_PER_ROUND_1_FOR_1;
        $is_specialized = $player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION,$player_character_weapon->getWeaponProficiencyId());
        if ($is_specialized) {
            $character_level = $character_details->getPrimaryClass()->getClassLevel();
            $weapon_subtype = $player_character_weapon->getMeleeWeaponSubtype();
            $weapon_proficiency_id = $player_character_weapon->getWeaponProficiencyId();
            $attacks_per_round = getSpecializedAttacksPerRound($character_level, WEAPON_TYPE_MELEE, $weapon_subtype, $weapon_proficiency_id);
        } else {
            $class_id = $character_details->getBestMeleeClassId();
            $class_level = $character_details->getLevelForClass($class_id);
            $attacks_per_round = getAttacksPerRound($class_id, $class_level, false, false, $player_character_weapon->getWeaponProficiencyId());
        }

        return getAttacksPerRoundDescription($attacks_per_round);
    }

    function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $melee_to_hit_calculator, RmCollectionCalculator $melee_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, $two_weapon_fighting_hand) {

        $hit_adj = $this->calculateHitAdj($melee_to_hit_calculator);
        $dmg_adj = $this->calculateDmgAdj($melee_damage_calculator);
        $hit_dmg_adj = $hit_adj . '/' . $dmg_adj;

        $weapon_speed = 200;
        if ($two_weapon_fighting_hand == TWO_WEAPON_FIGHTING_OFF_HAND) {
            $weapon_speed = $this->main_hand_weapon->getMeleeWeaponSpeed() + $this->off_hand_weapon->getMeleeWeaponSpeed();
        } else {
            $weapon_speed = $player_character_weapon->getMeleeWeaponSpeed();
        }

        $weapon_desc  = $this->buildRmChevronClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name);
        $weapon_desc .= getTwoWeaponFightHandDescription($two_weapon_fighting_hand);
        $weapon_desc .= '::' . $player_character_weapon->getWeaponDescription();

        $weapon_detail_entry  = HtmlHelper::buildDivStartTag($this->formatCellStyle(false));
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailLeft', $weapon_desc);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $weapon_speed);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $attacks_per_round);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMeleeWeaponDamage());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $hit_dmg_adj);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $weapon_detail_entry;
    }
}

?>