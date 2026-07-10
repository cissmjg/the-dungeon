<?php

require_once __DIR__ . '/../dbio/constants/skills.php';
require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
require_once __DIR__ . '/../dbio/constants/missileRanges.php';
require_once __DIR__ . '/../dbio/constants/attacksPerRound.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/rollModifier/rmUIContainer.php';
require_once __DIR__ . '/rollModifier/rmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../fa/faChevronIcon.php';
require_once __DIR__ . '/../fa/faChevronIndentedIcon.php';

require_once 'playerCharacterWeapon.php';
require_once 'playerCharacterSkillSet.php';
require_once 'characterDetails.php';
require_once 'attacksPerRoundCalculator.php';
require_once 'attributeMetadata.php';
require_once 'rowClassManager.php';

abstract class PlayerCharacterWeaponRendererBase {

    abstract public function getId();
    abstract public function getType();

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

    private $has_rendered = false;
    public function hasRendered() {
        $this->has_rendered;
    }

    public function setHasRendered($has_rendered) {
        $this->has_rendered = $has_rendered;
    }

    public function __construct(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager) {
        $this->player_character_weapon = $player_character_weapon;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->row_class_manager = $row_class_manager;
    }

    abstract function render();

    protected function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, AttacksPerRoundCalculator $attacks_per_round_calculator, $weapon_panel_name, $weapon_panel_icon_name, MissileRange $missile_range){
        return 'Base';        
    }

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

    protected function buildSkillList(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, $uses_point_blank_range) {
        if ($player_character_weapon->isMartialSkillWeapon()) {
            $mantis_leap_name = getSkillDescriptionFromSkillId(MANTIS_LEAP);
            $circle_kick_name = getSkillDescriptionFromSkillId(CIRCLE_KICK);
            if ($player_character_weapon->getWeaponDescription() == $mantis_leap_name || $player_character_weapon->getWeaponDescription() == $circle_kick_name) {
                return '';
            }
        }

        $output_html = '';
        $weapon_proficiency_id = $player_character_weapon->getWeaponProficiencyId();
        
        $quickdraw = $player_character_skill_set->getAllSkillInstancesForWeapon(QUICK_DRAW, $weapon_proficiency_id);
        if (!empty($quickdraw)) {
            $quickdraw_skill = $quickdraw[0];
            $output_html .= $quickdraw_skill->getPlayerCharacterSkillName() . '<br>';
        }

        $improved_critical = $player_character_skill_set->getAllSkillInstancesForWeapon(IMPROVED_CRITICAL, $weapon_proficiency_id);
        if (!empty($improved_critical)) {
            $improved_critical_skill = $improved_critical[0];
            $output_html .= $improved_critical_skill->getPlayerCharacterSkillName() . '<br>';
        }

        $specialization = $player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $weapon_proficiency_id);
        if (!empty($specialization) && $uses_point_blank_range) {
            $output_html .= 'Double damage at PB range<br>';
        }

        $precise_shot = $player_character_skill_set->getAllSkillInstances(PRECISE_SHOT);
        if (!empty($precise_shot) && $player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
            $precise_shot_skill = $precise_shot[0];
            $output_html .= $precise_shot_skill->getPlayerCharacterSkillName() . '<br>';
        }

        $sharp_shooting = $player_character_skill_set->getAllSkillInstances(SHARP_SHOOTING);
        if (!empty($sharp_shooting) && $player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
            $sharp_shooting_skill = $sharp_shooting[0];
            $output_html .= $sharp_shooting_skill->getPlayerCharacterSkillName() . '<br>';
        }

        $rapid_reload = $player_character_skill_set->getAllSkillInstancesForWeapon(RAPID_RELOAD, $weapon_proficiency_id);
        if (!empty($rapid_reload)) {
            if ($weapon_proficiency_id == LIGHT_CROSSBOW || $weapon_proficiency_id == GREAT_CROSSBOW || $weapon_proficiency_id == DOKYU || $weapon_proficiency_id == PISTOL_GRIP_CROSSBOW) {
                $rapid_reload_skill = $rapid_reload[0];
                $output_html .= $rapid_reload_skill->getPlayerCharacterSkillName() . '<br>';
            }
        }

        if ($weapon_proficiency_id == FIST) {
            $combat_reflexes = $player_character_skill_set->getAllSkillInstances(COMBAT_REFLEXES);
            if (!empty($combat_reflexes)) {
                $count_combat_reflexes = count($combat_reflexes);
                $combat_reflexes_skill = $combat_reflexes[0];
                $output_html .= sprintf("%s (%d)<br>", $combat_reflexes_skill->getPlayerCharacterSkillName(), $count_combat_reflexes);
            }

            $improved_unarmed_strike = $player_character_skill_set->getAllSkillInstances(IMPROVED_UNARMED_STRIKE);
            if (!empty($improved_unarmed_strike) && !$player_character_weapon->isMartialSkillWeapon()) {
                $improved_unarmed_strike_skill = $improved_unarmed_strike[0];
                $output_html .= $improved_unarmed_strike_skill->getPlayerCharacterSkillName() . '<br>';
            }

            $clever_wrestling = $player_character_skill_set->getAllSkillInstances(CLEVER_WRESTLING);
            if (!empty($clever_wrestling) && !$player_character_weapon->isMartialSkillWeapon()) {
                $clever_wrestling_skill = $clever_wrestling[0];
                $output_html .= $clever_wrestling_skill->getPlayerCharacterSkillName() . '<br>';
            }

            $close_quarters_fighting = $player_character_skill_set->getAllSkillInstances(CLOSE_QUARTERS_FIGHTING);
            if (!empty($close_quarters_fighting) && !$player_character_weapon->isMartialSkillWeapon()) {
                $close_quarters_fighting_skill = $close_quarters_fighting[0];
                $output_html .= $close_quarters_fighting_skill->getPlayerCharacterSkillName() . '<br>';
            }

            $eagle_claw = $player_character_skill_set->getAllSkillInstances(EAGLE_CLAW);
            if (!empty($eagle_claw) && !$player_character_weapon->isMartialSkillWeapon()) {
                $eagle_claw_skill = $eagle_claw[0];
                $output_html .= $eagle_claw_skill->getPlayerCharacterSkillName() . '<br>';
            }

            $dirty_fighting = $player_character_skill_set->getAllSkillInstances(DIRTY_FIGHTING);
            if (!empty($dirty_fighting) && !$player_character_weapon->isMartialSkillWeapon()) {
                $dirty_fighting_skill = $dirty_fighting[0];
                $output_html .= $dirty_fighting_skill->getPlayerCharacterSkillName() . '<br>';
            }
        }

        if (!empty($player_character_weapon->getPlayerNote1())) {
            $output_html .= $player_character_weapon->getPlayerNote1() . '<br>';
        }

        if (!empty($player_character_weapon->getPlayerNote2())) {
            $output_html .= $player_character_weapon->getPlayerNote2() . '<br>';
        }

        if (!empty($player_character_weapon->getPlayerNote3())) {
            $output_html .= $player_character_weapon->getPlayerNote3() . '<br>';
        }

        if (!empty($player_character_weapon->getMeleeAdditionalText())) {
            $output_html .= $player_character_weapon->getMeleeAdditionalText() . '<br>';
        }

        if (!empty($player_character_weapon->getMissileAdditionalText())) {
            $output_html .= $player_character_weapon->getMissileAdditionalText() . '<br>';
        }

        return $output_html;
    }

    private function hitDmgSkill($skill_id) {
        return ($skill_id == MANTIS_LEAP || $skill_id == CIRCLE_KICK || $skill_id == THROW_ANYTHING || $skill_id == DIRTY_FIGHTING || $skill_id == FIST_OF_IRON || $skill_id == WEAPON_FOCUS_ACCURACY || $skill_id == WEAPON_FOCUS_GREATER_ACCURACY || $skill_id == WEAPON_FOCUS_TECHNIQUE || $skill_id == WEAPON_FOCUS_GREATER_TECHNIQUE || $skill_id == CLERICS_PREFERRED_WEAPON || $skill_id == TWO_WEAPON_FIGHTING || $skill_id == DOUBLE_SPECIALIZATION);
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

    protected function calculateWeaponSpeed($weapon_speed, AttacksPerRound $attacks_per_round, $has_rapid_reload, $weapon_subtype, $number_of_hands, $weapon_proficiency_id) {

        $physical_weapon_style_2_attacks = false;
        $physical_weapon_style_1_attack = false;

        if (str_contains($weapon_speed, '/') && $number_of_hands != '1/2') {
            if ($weapon_proficiency_id != CALTROP) {
                $physical_weapon_style_2_attacks = true;
            } else {
                $physical_weapon_style_1_attack = true;
            }
        } else {
            $physical_weapon_style_1_attack = true;
        }

        $weapon_speed_final = '';
        switch ($attacks_per_round) {
            case AttacksPerRound::OneEvery3:
                $weapon_speed_final = $weapon_speed;
                break;

            case AttacksPerRound::OneEvery2:
                $weapon_speed_final = $weapon_speed;
                break;

            case AttacksPerRound::One:
                $weapon_speed_final = $weapon_speed;
                break;

            case AttacksPerRound::ThreeEvery2:
                $weapon_speed_final = $weapon_speed . '/(EoR)';
                break;

            case AttacksPerRound::Two:
                if ($has_rapid_reload && $weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    if ($physical_weapon_style_1_attack) {
                        $weapon_speed_final = $weapon_speed . '/8';
                    }

                    if ($physical_weapon_style_2_attacks) {
                        $weapon_speed_final = $weapon_speed;
                    }
                } else if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/EoR';
                } else if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed;
                }

                break;

            case AttacksPerRound::FiveEvery2:
                if ($has_rapid_reload && $weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $weapon_speed_final = $weapon_speed . '/8/(EoR)';
                } else if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/(EoR)';
                } else if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/(EoR)';
                }

                break;

            case AttacksPerRound::Three:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/EoR';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/EoR';
                }

                break;

            case AttacksPerRound::SevenEvery2:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/?/(EoR)';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/?/(EoR)';
                }

                break;

            case AttacksPerRound::Four:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/?/EoR';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/?/EoR';
                }

                break;

            case AttacksPerRound::NineEvery2:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/?/?/(EoR)';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/?/?/(EoR)';
                }

                break;
                
            case AttacksPerRound::Five:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/?/?/EoR';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/?/?/EoR';
                }

                break;

            case AttacksPerRound::Six:
                if ($physical_weapon_style_1_attack) {
                    $weapon_speed_final = $weapon_speed . '/?/?/?/?/EoR';
                }
                
                if ($physical_weapon_style_2_attacks) {
                    $weapon_speed_final = $weapon_speed . '/?/?/?/EoR';
                }

                break;

            default:
                $weapon_speed_final = $weapon_speed;
        }

        return $weapon_speed_final;
    }

    protected function formatDisplayId($weapon_panel_name) {
        return 'csd-' . $weapon_panel_name;
    }
}
?>