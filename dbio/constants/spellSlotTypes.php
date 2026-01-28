<?php

const BASE_SLOT_TYPE = 1;
const WISDOM_SLOT_TYPE = 2;
const EXTRA_SLOT_SKILL_SLOT_TYPE = 3;
const GM_EXTRA_SLOT_SKILL_SLOT_TYPE = 4;

function getSlotTypeID($slot_desc) {
    switch($slot_desc) {
        case 'Base':
            return BASE_SLOT_TYPE;
        case 'Wisdom':
            return WISDOM_SLOT_TYPE;
        case 'Extra Slot Skill':
            return EXTRA_SLOT_SKILL_SLOT_TYPE;
        case 'GM Extra Slot Skill':
            return GM_EXTRA_SLOT_SKILL_SLOT_TYPE;
    }
}
?>