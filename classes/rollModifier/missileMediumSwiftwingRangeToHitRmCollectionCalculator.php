<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';
    require_once 'rmCollectionCalculator.php';
    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';
    require_once 'missileMediumRangeToHitBaseRmCollectionCalculator.php';

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../../dbio/constants/characterRaces.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../webio/craftStatus.php';

    class MissileMediumSwiftwingRangeToHitRmCollectionCalculator extends MissileMediumRangeToHitBaseRmCollectionCalculator {

        private const MEDIUM_SWIFTWING_RANGE_PENALTY = -1;

        protected function getRmMediumRangePenalty() {
            $rm_medium_range_penalty_desc = "Medium Range Swiftwing";
            $rm_medium_range_penalty_modified = MissileMediumSwiftwingRangeToHitRmCollectionCalculator::MEDIUM_SWIFTWING_RANGE_PENALTY;
            $rm_medium_range = new RmFactor($rm_medium_range_penalty_desc, $rm_medium_range_penalty_modified);
            $rm_medium_range->setRmCategory(ROLL_MODIFIER_PENALTY);

            return $rm_medium_range;
        }
    }
?>
