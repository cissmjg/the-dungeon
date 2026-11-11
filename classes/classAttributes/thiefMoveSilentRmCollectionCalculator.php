<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefMoveSilentTrapRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

        $this->attribute_metadata = $attribute_metadata;
        $thief_level = $this->getEquivalentMoveSilentThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_move_silent = $this->getBaseMoveSilentPct($thief_level);
        $this->getRmCollection()->add($base_move_silent);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_move_silent = $this->getRacialMoveSilentPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_move_silent);

        $dexterity_move_silent = $this->getDexterityMoveSilent();
        $this->getRmCollection()->add($dexterity_move_silent);

        $stealthy_move_silent = $this->getStealthyMoveSilent($player_character_skill_set);
        if (!empty($stealthy_move_silent)) {
            $this->getRmCollection()->add($stealthy_move_silent);
        }
    }

    private function getBaseMoveSilentPct($thief_level) {
        $base_move_silent_desc = 'Base Move Silently';
        $base_move_silent_pct = $this->getThiefLookupFunction()->getBaseMoveSilently($thief_level);
        $base_move_silent = new RmFactor($base_move_silent_desc, $base_move_silent_pct);

        return $base_move_silent;
    }

    private function getRacialMoveSilentPct($thief_race, $thief_race_id) {
        $racial_adjustment_move_silent_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_move_silent_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForMoveSilently($thief_race_id);
        $racial_move_silent = new RmFactor($racial_adjustment_move_silent_desc, $racial_adjustment_move_silent_pct);

        return $racial_move_silent;
    }

    private function getDexterityMoveSilent() {
        $dexterity_adjustment_move_silent_desc = 'Dexterity Adjustment';
        $dexterity_adjustment_move_silent_pct = $this->attribute_metadata->getMoveSilentlyDexterityAdjustment();
        $dexterity_move_silent = new RmFactor($dexterity_adjustment_move_silent_desc, $dexterity_adjustment_move_silent_pct);

        return $dexterity_move_silent;
    }

    private function getEquivalentMoveSilentThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $thief_level = $character_details->getLevelForClass(THIEF);
        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        $move_silent_level = count($player_character_skill_set->getAllSkillInstances(MOVE_SILENTLY));
        $has_stealthy = count($player_character_skill_set->getAllSkillInstances(STEALTHY)) > 0;

        if ($thief_level == 0 && $assassin_level == 0 && $has_stealthy) {
            return 2 + $move_silent_level;   // Stealthy skill allows non 'thieves' to Move Silently as a 2nd level thief
        }

        if ($thief_level > 0) {
            return $move_silent_level + $thief_level;
        } else if ($assassin_level > 2) {
            return $move_silent_level + ($assassin_level - 2);
        }

        return $move_silent_level;
    }

    private function getStealthyMoveSilent(PlayerCharacterSkillSet $player_character_skill_set) {
        $stealthy_move_silent = null;
        $stealthy_move_silent_pct = $this->getStealthySkillPct($player_character_skill_set);
        if ($stealthy_move_silent_pct > 0) {
            $stealthy_move_silent_desc = 'Stealthy (skill)';
            $stealthy_move_silent = new RmFactor($stealthy_move_silent_desc, $stealthy_move_silent_pct);
        }

        return $stealthy_move_silent;
    }

    private function getStealthySkillPct(PlayerCharacterSkillSet $player_character_skill_set) {
        $stealthy_move_silent_pct = 0;
        $stealthy_skill_count = count($player_character_skill_set->getAllSkillInstances(STEALTHY));
        if ($stealthy_skill_count > 0) {
            $stealthy_move_silent_pct = $stealthy_skill_count * 10;  // Each instance of Stealthy grants +10% Move Silently
        }

        return $stealthy_move_silent_pct;
    }
}
?>
