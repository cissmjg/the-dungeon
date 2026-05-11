<?php
require_once 'playerCharacterWeaponRenderer.php';
require_once 'rowClassManager.php';

require_once __DIR__ . '/../dbio/constants/skills.php';
require_once __DIR__ . '/../dbio/constants/missileRanges.php';
require_once __DIR__ . '/../dbio/constants/arrowTypes.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/rollModifier/missileArcherLongRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherLongSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherMediumRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherMediumSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherPointBlankToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherShortRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileElvenCavalierLongRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileElvenCavalierLongSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileElvenCavalierMediumRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileElvenCavalierMediumSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileElvenCavalierShortRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileLongRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileLongSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileMediumRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileMediumSwiftwingRangeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missilePointBlankToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileShortRangeToHitRmCollectionCalculator.php';

require_once __DIR__ . '/rollModifier/missileArcherLongRangeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherMediumRangeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherPointBlankDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileArcherShortRangeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileLongRangeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileMediumRangeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missilePointBlankDamageRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/missileShortRangeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';

class PlayerCharacterMissileWeaponRenderer extends PlayerCharacterWeaponRenderer {

    private $uses_point_blank_range = false;
    private $uses_short_range = false;
    private $uses_medium_range = false;
    private $uses_long_range = false;
    private $uses_medium_swiftwing_range = false;
    private $uses_long_swiftwing_range = false;
    private $medium_range_cell_style = '';
    private $long_range_cell_style = '';

    public function __construct(PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, RowClassManager $row_class_manager){
        parent::__construct($player_character_weapon, $player_character_skill_set, $character_details, $attribute_metadata, $row_class_manager);
    }

    public function render() {
        if ($this->player_character_weapon->getMissileWeaponType() != WEAPON_TYPE_MISSILE) {
            return '';
        }
        
        $attacks_per_round = $this->calculateAttacksPerRound($this->player_character_skill_set, $this->player_character_weapon, $this->character_details);

        $weapon_panel = '';
        $weapon_panel_section_name = $this->combat_mode == COMBAT_MODE_MOUNTED ? "mounted-" : "unmounted-";

        $this->uses_point_blank_range = $this->usesPointBlankRange($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon());
        $this->uses_short_range = $this->usesShortRange($this->getPlayerCharacterWeapon());
        $this->uses_medium_range = $this->usesMediumRange($this->getPlayerCharacterWeapon());
        $this->uses_medium_swiftwing_range = $this->usesMediumSwiftwingRange($this->getPlayerCharacterWeapon());
        $this->uses_long_range = $this->usesLongRange($this->getPlayerCharacterWeapon());
        $this->uses_long_swiftwing_range = $this->usesLongSwiftwingRange($this->getPlayerCharacterWeapon());

        // Point Blank Range
        if ($this->uses_point_blank_range) {
            $point_blank_to_hit_calculator = $this->getPointBlankToHitCalculator($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon());
            $point_blank_damage_calculator = $this->getPointBlankDamageCalculator($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon());
            $weapon_panel_name = 'weapon-pb-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-pb-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::PointBlank, $point_blank_to_hit_calculator, $point_blank_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        // Short Range
        if ($this->uses_short_range) {
            $short_range_to_hit_calculator = $this->getShortRangeToHitCalculator($this->getCharacterDetails());
            $short_range_damage_calculator = $this->getShortRangeDamageCalculator($this->getCharacterDetails());
            $weapon_panel_name = 'weapon-short-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-short-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::Short, $short_range_to_hit_calculator, $short_range_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        // Medium Range
        if ($this->uses_medium_range) {
            $medium_range_to_hit_calculator = $this->getMediumRangeToHitCalculator($this->getCharacterDetails());
            $medium_range_damage_calculator = $this->getMediumRangeDamageCalculator($this->getCharacterDetails());
            $weapon_panel_name = 'weapon-med-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-med-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::Medium, $medium_range_to_hit_calculator, $medium_range_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        // If Bow, format Swiftwing panel for Medium Range, panel is hidden initially
        if ($this->uses_medium_swiftwing_range) {
            $medium_range_swiftwing_to_hit_calculator = $this->getSwiftwingMediumRangeToHitCalculator($this->getCharacterDetails());
            $medium_range_damage_calculator = $this->getMediumRangeDamageCalculator($this->getCharacterDetails());
            $weapon_panel_name = 'weapon-med-sw-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-med-sw-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::MediumSwiftwing, $medium_range_swiftwing_to_hit_calculator, $medium_range_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        if ($this->uses_long_range) {
            $long_range_to_hit_calculator = $this->getLongRangeToHitCalculator($this->getCharacterDetails());
            $long_range_damage_calculator = $this->getLongRangeDamageCalculator($this->getCharacterDetails());
            $weapon_panel_name = 'weapon-long-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-long-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::Long, $long_range_to_hit_calculator, $long_range_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        // If Bow, format Swiftwing panel for Long Range, panel is hidden initially
        if ($this->uses_long_swiftwing_range) {
            $long_swiftwing_to_hit_calculator = $this->getLongSwiftwingRangeToHitCalculator($this->getCharacterDetails());
            $long_range_damage_calculator = $this->getLongRangeDamageCalculator($this->getCharacterDetails());
            $weapon_panel_name = 'weapon-long-sw-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel_icon_name = 'weapon-long-sw-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
            $weapon_panel .= $this->renderPanel(MissileRange::LongSwiftwing, $long_swiftwing_to_hit_calculator, $long_range_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        }

        return $weapon_panel;
    }

    private function renderPanel(MissileRange $missile_range, RmCollectionCalculator $to_hit_calculator, RmCollectionCalculator $damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name) {
        $to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);
        $damage_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $weapon_panel  = $this->buildWeaponDetailEntry($this->player_character_weapon, $to_hit_calculator, $damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, $missile_range);
        $weapon_panel .= $this->buildRmWeaponPanel($to_hit_calculator, $damage_calculator, $weapon_panel_name);

        return $weapon_panel;
    }

    public function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $melee_to_hit_calculator, RmCollectionCalculator $melee_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name, MissileRange $missile_range) {

        $hit_adj = $this->calculateHitAdj($melee_to_hit_calculator);
        $dmg_adj = $this->calculateDmgAdj($melee_damage_calculator);
        $hit_dmg_adj = $hit_adj . '/' . $dmg_adj;

        $weapon_desc = '';
        $render_header = false;
        if ($missile_range == MissileRange::PointBlank) {
            $weapon_desc = $this->buildRmChevronClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $player_character_weapon->getWeaponDescription();
            $render_header = true;
        } else if ($missile_range == MissileRange::Short && !$this->uses_point_blank_range) {
            $render_header = true;
            if ($player_character_weapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
                $weapon_desc = $this->buildRmChevronIndentedClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $missile_range->value;
            } else {
                $weapon_desc = $this->buildRmChevronClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $player_character_weapon->getWeaponDescription();
            }
        } else {
            $weapon_desc = $this->buildRmChevronIndentedClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $missile_range->value;
        }

        $weapon_range = $this->formatRange($missile_range, $player_character_weapon);

        $row_id = $this->buildWeaponHeaderId($missile_range, $player_character_weapon);

        // Initial Swiftwing arrow sections are hidden
        $is_hidden = false;
        if ($missile_range == MissileRange::MediumSwiftwing || $missile_range == MissileRange::LongSwiftwing) {
            $is_hidden = true;
        }

        // Cell style
        $cell_style = '';

        // Save the cell style so that Medium and MediumSwiftwing use the same style
        if ($missile_range == MissileRange::Medium) {
            $cell_style = $this->formatCellStyle($is_hidden);
            $this->medium_range_cell_style = $cell_style;
        } else if ($missile_range == MissileRange::Long) {
            $cell_style = $this->formatCellStyle($is_hidden);
            $this->long_range_cell_style = $cell_style;
        } else if ($missile_range == MissileRange::MediumSwiftwing) {
            $cell_style = $this->medium_range_cell_style;
        } else if ($missile_range == MissileRange::LongSwiftwing) {
            $cell_style = $this->long_range_cell_style;
        } else {
            $cell_style = $this->formatCellStyle($is_hidden);
        }

        $weapon_detail_entry = '';
        if ($is_hidden) {
            $weapon_detail_entry .= '<div id="' . $row_id . '" class="' . $cell_style . '" style="display: none;">';
        } else {
            $weapon_detail_entry .= HtmlHelper::buildDivStartTagWithId($cell_style, $row_id, false);
        }
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailLeft', $weapon_desc);
        if ($render_header) {
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMissileWeaponSpeed());
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $attacks_per_round);
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $this->formatDamage($player_character_weapon));
        } else {
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', '&nbsp;');
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', '&nbsp;');
            $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', '&nbsp;');
        }
        $range_cell_id = $this->buildRangeCellId($missile_range, $player_character_weapon);
        $weapon_detail_entry .= HtmlHelper::buildDivTagWithId($range_cell_id, 'rmWeaponDetailCenter', $weapon_range);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $hit_dmg_adj);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $weapon_detail_entry;
    }

    public function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details) {
        $is_preferred = $this->isPreferred($player_character_weapon, $player_character_skill_set, $character_details);
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
            $attacks_per_round = getAttacksPerRound($class_id, $class_level, $is_preferred, $this->getCombatMode() == COMBAT_MODE_MOUNTED, $player_character_weapon->getWeaponProficiencyId());
        }

        return getAttacksPerRoundDescription($attacks_per_round);
    }

    private function getPointBlankToHitCalculator(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
        $rm_pb_calculator = null;
        $specialized_in_weapon = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $player_character_weapon->getWeaponProficiencyId())) > 0;

        $character_class_id = $character_details->getFighterTypeClassId();
        $is_archer = $this->isArcher($character_details);
        if ($is_archer && $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
            $rm_pb_calculator = new MissileArcherPointBlankToHitRmCollectionCalculator();
        } else if ($character_class_id != 0 && $specialized_in_weapon) {
            $rm_pb_calculator = new MissilePointBlankToHitRmCollectionCalculator();
        }

        return $rm_pb_calculator;
    }

    private function getPointBlankDamageCalculator(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
        $rm_pb_calculator = null;
        $specialized_in_weapon = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $player_character_weapon->getWeaponProficiencyId())) > 0;

        $character_class_id = $character_details->getFighterTypeClassId();
        $is_archer = $this->isArcher($character_details);
        if ($is_archer && $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
            $rm_pb_calculator = new MissileArcherPointBlankDamageRmCollectionCalculator();
        } else if ($character_class_id != 0 && $specialized_in_weapon) {
            $rm_pb_calculator = new MissilePointBlankDamageRmCollectionCalculator();
        }

        return $rm_pb_calculator;
    }

    private function usesPointBlankRange(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
        $uses_point_blank_range = false;
        $specialized_in_weapon = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $player_character_weapon->getWeaponProficiencyId())) > 0;

        $character_class_id = $character_details->getFighterTypeClassId();
        $is_archer = $this->isArcher($character_details);
        if ($is_archer && $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
            $uses_point_blank_range = true;
        } else if ($character_class_id != 0 && $specialized_in_weapon) {
            $uses_point_blank_range = true;
        }

        return $uses_point_blank_range;
    }

    private function usesShortRange(PlayerCharacterWeapon $player_character_weapon) {
        return $player_character_weapon->getMissileShortRange() != null;
    }

    private function getShortRangeToHitCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($character_details->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
            return new missileElvenCavalierShortRangeToHitRmCollectionCalculator();
        } else if ($is_archer) {
            return new missileArcherShortRangeToHitRmCollectionCalculator();
        } else {
            return  new missileShortRangeToHitRmCollectionCalculator();
        }
    }

    private function getShortRangeDamageCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($is_archer) {
            return new missileArcherShortRangeDamageRmCollectionCalculator();
        } else {
            return  new missileShortRangeDamageRmCollectionCalculator();
        }
    }

    private function usesMediumRange(PlayerCharacterWeapon $player_character_weapon) {
        return $player_character_weapon->getMissileMediumRange() != null;
    }

    private function usesMediumSwiftwingRange(PlayerCharacterWeapon $player_character_weapon) {
        return ($player_character_weapon->getMissileMediumRange() != null && $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW);
    }

    private function getMediumRangeToHitCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($character_details->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
            return new missileElvenCavalierMediumRangeToHitRmCollectionCalculator();
        } else if ($is_archer) {
            return new missileArcherMediumRangeToHitRmCollectionCalculator();
        } else {
            return  new missileMediumRangeToHitRmCollectionCalculator();
        }
    }

    private function getSwiftwingMediumRangeToHitCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($character_details->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
            return new missileElvenCavalierMediumSwiftwingRangeToHitRmCollectionCalculator();
        } else if ($is_archer) {
            return new missileArcherMediumSwiftwingRangeToHitRmCollectionCalculator();
        } else {
            return  new missileMediumSwiftwingRangeToHitRmCollectionCalculator();
        }
    }

    private function getMediumRangeDamageCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($is_archer) {
            return new missileArcherMediumRangeDamageRmCollectionCalculator();
        } else {
            return  new missileMediumRangeDamageRmCollectionCalculator();
        }
    }

    private function usesLongRange(PlayerCharacterWeapon $player_character_weapon) {
        return $player_character_weapon->getMissileLongRange() != null;
    }

    private function usesLongSwiftwingRange(PlayerCharacterWeapon $player_character_weapon) {
        return ($player_character_weapon->getMissileLongRange() != null && $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW);
    }

    private function getLongRangeToHitCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($character_details->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
            return new missileElvenCavalierLongRangeToHitRmCollectionCalculator();
        } else if ($is_archer) {
            return new missileArcherLongRangeToHitRmCollectionCalculator();
        } else {
            return  new missileLongRangeToHitRmCollectionCalculator();
        }
    }

    private function getLongSwiftwingRangeToHitCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($character_details->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
            return new missileElvenCavalierLongSwiftwingRangeToHitRmCollectionCalculator();
        } else if ($is_archer) {
            return new missileArcherLongSwiftwingRangeToHitRmCollectionCalculator();
        } else {
            return  new missileLongSwiftwingRangeToHitRmCollectionCalculator();
        }
    }

    private function getLongRangeDamageCalculator(CharacterDetails $character_details) {
        $is_archer = $this->isArcher($character_details);

        if ($is_archer) {
            return new missileArcherLongRangeDamageRmCollectionCalculator();
        } else {
            return  new missileLongRangeDamageRmCollectionCalculator();
        }
    }

    private function formatRange(MissileRange $missile_range, PlayerCharacterWeapon $player_character_weapon) {
        switch($missile_range) {
            case MissileRange::PointBlank:
                return "up to 3";
            case MissileRange::Short:
                return $player_character_weapon->getMissileShortRange();
            case MissileRange::Medium:
            case MissileRange::MediumSwiftwing:
                $start = $player_character_weapon->getMissileShortRange() + 1;
                return $start . ' - ' . $player_character_weapon->getMissileMediumRange();
            case MissileRange::Long:
            case MissileRange::LongSwiftwing:
                $start = $player_character_weapon->getMissileMediumRange() + 1;
                return $start . ' - ' . $player_character_weapon->getMissileLongRange();
            default:
                return '???';
        }
    }

    private function formatDamage(PlayerCharacterWeapon $player_character_weapon) {
        if ($player_character_weapon->getWeaponProficiencyId() == SLING) {
            // Sling Ammo
            return $this->buildSlingAmmoSelector($player_character_weapon);
        }

        if ($player_character_weapon->getMissileWeaponDamage() != '-') {
            return $player_character_weapon->getMissileWeaponDamage();
        }

        if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
            // Arrows
            return $this->buildArrowSelector($player_character_weapon);
        }
    }

    private function isArcher(CharacterDetails $character_details) {
        $character_class_id = $character_details->getFighterTypeClassId();
        return ($character_class_id == ARCHER || $character_class_id == ARCHER_RANGER);
    }

    private function buildWeaponHeaderId(MissileRange $missile_range, $player_character_weapon) {
        return sprintf("weapon-header-%s-%s-%s", $missile_range->value, getMountedCombatModeDescription($this->getCombatMode()), $player_character_weapon->getWeaponId());
    }

    private function buildRangeCellId(MissileRange $missile_range, $player_character_weapon) {
        return sprintf("weapon-range-%s-%s-%s", $missile_range->value, getMountedCombatModeDescription($this->getCombatMode()), $player_character_weapon->getWeaponId());
    }

    private function buildArrowSelector(PlayerCharacterWeapon $player_character_weapon) {
        $selector_id = 'arrow-select-' . getMountedCombatModeDescription($this->getCombatMode()) . '-' . $player_character_weapon->getWeaponId();
        $quoted_selector_id = "'" . $selector_id . "'";
        $med_range_id = "'" . $this->buildWeaponHeaderId(MissileRange::Medium, $player_character_weapon) . "'";
        $med_sw_range_id = "'" . $this->buildWeaponHeaderId(MissileRange::MediumSwiftwing, $player_character_weapon) . "'";
        $long_range_id = "'" . $this->buildWeaponHeaderId(MissileRange::Long, $player_character_weapon) . "'";
        $long_sw_range_id = "'" . $this->buildWeaponHeaderId(MissileRange::LongSwiftwing, $player_character_weapon) . "'";

        $output_html  = PHP_EOL . '<select id="' . $selector_id . '" onchange="arrowTypeChange(' . $quoted_selector_id . ', ' . $med_range_id . ', ' . $med_sw_range_id . ', ' . $long_range_id . ', ' . $long_sw_range_id . ');">' . PHP_EOL;
        $output_html .= $this->buildArrowTypeOptions();
        $output_html .= '</select>' . PHP_EOL;

        return $output_html;
    }

    private function buildArrowTypeOptions() {
        $output_html = '';
        $all_arrow_types = ArrowType::cases();
        foreach($all_arrow_types AS $arrow_type) {
            $output_html .= '<option value="' . $arrow_type->value . '">' . $arrow_type->value . '</option>' . PHP_EOL;
        }

        return $output_html;
    }

    private function buildSlingAmmoSelector(PlayerCharacterWeapon $player_character_weapon) {
        $selector_id = 'sling-ammo-select-' . getMountedCombatModeDescription($this->getCombatMode()) . $player_character_weapon->getWeaponId();
        $quoted_selector_id = "'" . $selector_id . "'";
        $short_range_id = "'" . $this->buildRangeCellId(MissileRange::Short, $player_character_weapon) . "'";
        $medium_range_id = "'" . $this->buildRangeCellId(MissileRange::Medium, $player_character_weapon) . "'";
        $long_range_id = "'" . $this->buildRangeCellId(MissileRange::Long, $player_character_weapon) . "'";

        $output_html  = PHP_EOL . '<select id="' . $selector_id . '" onchange="slingAmmoChange(' . $quoted_selector_id . ', ' . $short_range_id . ', ' . $medium_range_id . ', ' . $long_range_id . ');">' . PHP_EOL;
        $output_html .= $this->buildSlingAmmoOptions();
        $output_html .= '</select>' . PHP_EOL;

        return $output_html;
    }

    private function buildSlingAmmoOptions() {
        $output_html  = '';
        $output_html .= '<option value="Bullet">2d4/2d4+1</option>' . PHP_EOL;
        $output_html .= '<option value="Stone">d8/2d4</option>' . PHP_EOL;

        return $output_html;
    }
}
?>