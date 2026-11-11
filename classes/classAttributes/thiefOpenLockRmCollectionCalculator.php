<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefOpenLockRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

        $this->attribute_metadata = $attribute_metadata;
        $thief_level = $this->getEquivalentOpenLockThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_open_lock = $this->getBaseOpenLockPct($thief_level);
        $this->getRmCollection()->add($base_open_lock);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_open_lock = $this->getRacialOpenLockPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_open_lock);

        $dexterity_open_lock = $this->getDexterityOpenLock();
        $this->getRmCollection()->add($dexterity_open_lock);
    }

    private function getBaseOpenLockPct($thief_level) {
        $base_open_lock_desc = 'Base Open Lock';
        $base_open_lock_pct = $this->getThiefLookupFunction()->getBaseOpenLocks($thief_level);
        $base_open_lock = new RmFactor($base_open_lock_desc, $base_open_lock_pct);

        return $base_open_lock;
    }

    private function getRacialOpenLockPct($thief_race, $thief_race_id) {
        $racial_adjustment_open_lock_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_open_lock_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForOpenLocks($thief_race_id);
        $racial_open_lock = new RmFactor($racial_adjustment_open_lock_desc, $racial_adjustment_open_lock_pct);

        return $racial_open_lock;
    }

    private function getDexterityOpenLock() {
        $dexterity_adjustment_open_lock_desc = 'Dexterity Adjustment';
        $dexterity_adjustment_open_lock_pct = $this->attribute_metadata->getOpenLocksDexterityAdjustment();
        $dexterity_open_lock = new RmFactor($dexterity_adjustment_open_lock_desc, $dexterity_adjustment_open_lock_pct);

        return $dexterity_open_lock;
    }

    private function getEquivalentOpenLockThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $open_lock_level = count($player_character_skill_set->getAllSkillInstances(OPEN_LOCK));
        $thief_level = $character_details->getLevelForClass(THIEF);
        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($thief_level > 0) {
            return $open_lock_level + $thief_level;
        } else if ($assassin_level > 2) {
            return $open_lock_level + ($assassin_level - 2);
        }

        return $open_lock_level;
    }
}
?>
