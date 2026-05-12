<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';
    require_once 'rmCollectionCalculator.php';
    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';
    require_once 'missileLongRangeToHitBaseRmCollectionCalculator.php';

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../../dbio/constants/characterRaces.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../webio/craftStatus.php';

    class MissileLongSwiftwingRangeToHitRmCollectionCalculator extends MissileLongRangeToHitBaseRmCollectionCalculator {

        private const LONG_RANGE_PENALTY = -3;

        protected function getRmLongRangePenalty() {
            $rm_long_range_penalty_desc = "Long Range";
            $rm_long_range_penalty_modified = MissileLongSwiftwingRangeToHitRmCollectionCalculator::LONG_RANGE_PENALTY;
            $rm_long_range = new RmFactor($rm_long_range_penalty_desc, $rm_long_range_penalty_modified);
            $rm_long_range->setRmCategory(ROLL_MODIFIER_PENALTY);

            return $rm_long_range;
        }
    }
?>
