<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefPickPocketRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        $this->attribute_metadata = $attribute_metadata;

        $thief_level = $this->getEquivalentPickPocketThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_pick_pocket = $this->getBasePickPocketPct($thief_level);
        $this->getRmCollection()->add($base_pick_pocket);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_pick_pocket = $this->getRacialPickPocketPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_pick_pocket);

        $dexterity_pick_pocket = $this->getDexterityPickPocket();
        $this->getRmCollection()->add($dexterity_pick_pocket);
    }

    private function getBasePickPocketPct($thief_level) {
        $base_pick_pocket_desc = 'Base Pick Pocket';
        $base_pick_pocket_pct = $this->getThiefLookupFunction()->getBasePickPocket($thief_level);
        $base_pick_pocket = new RmFactor($base_pick_pocket_desc, $base_pick_pocket_pct);

        return $base_pick_pocket;
    }

    private function getRacialPickPocketPct($thief_race, $thief_race_id) {
        $racial_adjustment_pick_pocket_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_pick_pocket_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForPickPocket($thief_race_id);
        $racial_pick_pocket = new RmFactor($racial_adjustment_pick_pocket_desc, $racial_adjustment_pick_pocket_pct);

        return $racial_pick_pocket;
    }

    private function getDexterityPickPocket() {
        $dexterity_adjustment_pick_pocket_desc = 'Dexterity Adjustment';
        $dexterity_adjustment_pick_pocket_pct = $this->attribute_metadata->getPickPocketDexterityAdjustment();
        $dexterity_pick_pocket = new RmFactor($dexterity_adjustment_pick_pocket_desc, $dexterity_adjustment_pick_pocket_pct);

        return $dexterity_pick_pocket;
    }

    private function getEquivalentPickPocketThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $pick_pocket_level = count($player_character_skill_set->getAllSkillInstances(PICK_POCKET));
        $thief_level = $character_details->getLevelForClass(THIEF);
        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($thief_level > 0) {
            return $pick_pocket_level + $thief_level;
        } else if ($assassin_level > 2) {
            return $pick_pocket_level + ($assassin_level - 2);
        }

        return $pick_pocket_level;
    }
}
?>
