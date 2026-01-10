function castGMSpell(spellCatalogIdValue, spellLevelValue, spellDurationValue, spellCastingTimeValue) {
    let jqSpellCatalogIdTag = '#spellCatalogId';
    $(jqSpellCatalogIdTag).val(spellCatalogIdValue);

    let jqSpellLevelTag = "#spellLevel";
    $(jqSpellLevelTag).val(spellLevelValue);

    let jqSpellDurationTag = "#spellDuration";
    $(jqSpellDurationTag).val(spellDurationValue);

    let jqSpellCastingTag = "#spellCastingTime";
    $(jqSpellCastingTag).val(spellCastingTimeValue);

    submitTheForm('slot-action-form', 'castGMSpellCharacterAction', 'castGMSpell');
}

function recoverSpellPoints() {
    let jqInputHoursOfSleep = $("#hoursOfSleepInput").val();
    let jqHoursOfSleepTag = "#hoursOfSleep";
    $(jqHoursOfSleepTag).val(jqInputHoursOfSleep);

    submitTheForm('recover-spell-points', 'recoverSpellPointsCharacterAction', 'recoverSpellPointsBySleep');
}

function showCantrip() {
    let cantripSlotID = $('#available_cantrip').val();
    if (cantripSlotID == "slot-action-row-select") {
            return;
    }

    let theCantripRow = $("#" + cantripSlotID);
    if (theCantripRow.is(":hidden")) {
        theCantripRow.show();
    } else {
        theCantripRow.hide();
    }
}

function submitStopActionForm(spell_slot_id, character_action) {
    let spellslotIdTag = $("#spellSlotId");
    spellslotIdTag.val(spell_slot_id);

    submitTheForm("stop-action-form", "stopGMSpellCharacterAction", character_action);
}