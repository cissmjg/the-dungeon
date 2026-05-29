<?php

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

    abstract function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, AttacksPerRoundCalculator $attacks_per_round_calculator, $weapon_panel_name, $weapon_panel_icon_name, MissileRange $missile_range);

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
                    $weapon_speed_final = $weapon_speed . '/8';
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
}
?>