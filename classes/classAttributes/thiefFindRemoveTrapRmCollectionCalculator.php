<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefFindRemoveTrapRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

        $this->attribute_metadata = $attribute_metadata;
        $thief_level = $this->getEquivalentFindRemoveTrapThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_find_remove_trap = $this->getBaseFindRemoveTrapPct($thief_level);
        $this->getRmCollection()->add($base_find_remove_trap);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_find_remove_trap = $this->getRacialFindRemoveTrapPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_find_remove_trap);

        $dexterity_find_remove_trap = $this->getDexterityFindRemoveTrap();
        $this->getRmCollection()->add($dexterity_find_remove_trap);
    }

    private function getBaseFindRemoveTrapPct($thief_level) {
        $base_find_remove_trap_desc = 'Base Find Remove Traps';
        $base_find_remove_trap_pct = $this->getThiefLookupFunction()->getBaseFindRemoveTraps($thief_level);
        $base_find_remove_trap = new RmFactor($base_find_remove_trap_desc, $base_find_remove_trap_pct);

        return $base_find_remove_trap;
    }

    private function getRacialFindRemoveTrapPct($thief_race, $thief_race_id) {
        $racial_adjustment_find_remove_trap_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_find_remove_trap_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForFindRemoveTraps($thief_race_id);
        $racial_find_remove_trap = new RmFactor($racial_adjustment_find_remove_trap_desc, $racial_adjustment_find_remove_trap_pct);

        return $racial_find_remove_trap;
    }

    private function getDexterityFindRemoveTrap() {
        $dexterity_adjustment_find_remove_trap_desc = 'Dexterity Adjustment';
        $dexterity_adjustment_find_remove_trap_pct = $this->attribute_metadata->getFindRemoveTrapsDexterityAdjustment();
        $dexterity_find_remove_trap = new RmFactor($dexterity_adjustment_find_remove_trap_desc, $dexterity_adjustment_find_remove_trap_pct);

        return $dexterity_find_remove_trap;
    }

    private function getEquivalentFindRemoveTrapThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $find_remove_trap_level = count($player_character_skill_set->getAllSkillInstances(DISABLE_DEVICE));
        $thief_level = $character_details->getLevelForClass(THIEF);
        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($thief_level > 0) {
            return $find_remove_trap_level + $thief_level;
        } else if ($assassin_level > 2) {
            return $find_remove_trap_level + ($assassin_level - 2);
        }

        return $find_remove_trap_level;
    }
}
?>
