function castGMSpell(spellCatalogIdValue, spellLevelValue, spellDurationValue, spellCastingTimeValue) {
    let theSpellCatalogIdTag =  document.getElementById(SPELL_CATALOG_ID);
    if (theSpellCatalogIdTag != null) {
        theSpellCatalogIdTag.value = spellCatalogIdValue;
    } else {
        alert("Spell Catalog ID tag: SPELL_CATALOG_ID not found");
        return false;
    }

    let theSpellLevelTag =  document.getElementById(SPELL_LEVEL);
    if (theSpellLevelTag != null) {
        theSpellLevelTag.value = spellLevelValue;
    } else {
        alert("Spell Level tag: SPELL_LEVEL not found");
        return false;
    }

    let theSpellDurationTag =  document.getElementById(SPELL_DURATION);
    if (theSpellDurationTag != null) {
        theSpellDurationTag.value = spellDurationValue;
    } else {
        alert("Spell Duration tag: SPELL_DURATION not found");
        return false;
    }

    let theSpellCastingTag =  document.getElementById(SPELL_CASTING_TIME);
    if (theSpellCastingTag != null) {
        theSpellCastingTag.value = spellCastingTimeValue;
    } else {
        alert("Spell Casting tag: SPELL_CASTING_TIME not found");
        return false;
    }

    submitTheForm('slot-action-form', 'castGMSpellCharacterAction', 'castGMSpell');
}

function recoverSpellPoints() {
    let theInputHoursOfSleepTag = document.getElementById('hoursOfSleepInput');
    if (theInputHoursOfSleepTag == null) {
        alert("Hours of Sleep input tag: 'hoursOfSleepInput' not found");
        return false;
    }

    let theHoursOfSleepFormTag = document.getElementById(HOURS_OF_SLEEP);
    if (theHoursOfSleepFormTag == null) {
        alert("Hours of Sleep form tag: HOURS_OF_SLEEP not found");
        return false;
    }

    theHoursOfSleepFormTag.value = theInputHoursOfSleepTag.value;

    submitTheForm('recover-spell-points', 'recoverSpellPointsCharacterAction', 'recoverSpellPointsBySleep');
}

function showCantrip() {
    let theCantripDropdownTag = document.getElementById('available_cantrip');
    if (theCantripDropdownTag == null) {
        alert("Available cantrips select tag: 'available_cantrip' not found");
        return false;
    }

    const cantripSlotID = theCantripDropdownTag.value;

    let theCantripRow = document.getElementById(cantripSlotID);
    if (theCantripRow == null) {
        alert("Cantrip table row: '" + cantripSlotID + "' not found");
        return false;
    }

    theCantripRow.hidden = !theCantripRow.hidden;
}

function submitStopActionForm(spell_slot_id, character_action) {
    let spell_slot_id_element = document.getElementById("spellSlotId");
    if (spell_slot_id_element != null) {
    } else {
        alert ("Slot Action Form 'Spell Slot ID' with ID " + spell_slot_id + " not found");
        return false;
    }
    spell_slot_id_element.value = spell_slot_id;

    submitTheForm("stop-action-form", "stopGMSpellCharacterAction", character_action);
}