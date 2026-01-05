<?php

    const SPELL_TYPE_CLERIC = 1;	
    const SPELL_TYPE_DRUID = 2;	
    const SPELL_TYPE_HEALER = 3;	
    const SPELL_TYPE_MAGIC_USER = 4;	
    const SPELL_TYPE_ILLUSIONIST = 5;	
    const SPELL_TYPE_N_A = 7;	
    const SPELL_TYPE_CANTRIP = 8;	
    const SPELL_TYPE_SHUKENJA = 9;	
    const SPELL_TYPE_WU_JEN = 10;
    const SPELL_TYPE_UNDEFINED = -1;

function getSpellTypeIDFromName($spell_type_name) {
    switch($spell_type_name) {
        case "Cleric":
            return SPELL_TYPE_CLERIC;
        case "Druid":
            return SPELL_TYPE_DRUID;	
        case "Healer":
            return SPELL_TYPE_HEALER;
        case "Magic-User":
            return SPELL_TYPE_MAGIC_USER;
        case "Illusionist":
            return SPELL_TYPE_ILLUSIONIST;	
        case "N/A":
            return SPELL_TYPE_N_A;
        case "CANTRIP":
            return SPELL_TYPE_CANTRIP;
        case "Shukenja":
            return SPELL_TYPE_SHUKENJA;	
        case "Wu Jen":
            return SPELL_TYPE_WU_JEN;
        default:
            return SPELL_TYPE_UNDEFINED;
    }
}

function getSpellTypeDesc($spell_type_id) {
    switch($spell_type_id) {
        case SPELL_TYPE_CLERIC:	
            return "Cleric";	
        case SPELL_TYPE_DRUID:	
            return "Druid";	
        case SPELL_TYPE_HEALER:	
            return "Healer";	
        case SPELL_TYPE_MAGIC_USER:	
            return "Magic-User";	
        case SPELL_TYPE_ILLUSIONIST:	
            return "Illusionist";	
        case SPELL_TYPE_N_A:	
            return "N/A";	
        case SPELL_TYPE_CANTRIP:	
            return "CANTRIP";	
        case SPELL_TYPE_SHUKENJA:	
            return "Shukenja";	
        case SPELL_TYPE_WU_JEN:	
            return "Wu Jen";	
        default:
            return "UNDEFINED";
    }
}

?>
