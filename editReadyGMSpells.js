import { submitTheCharacterActionForm } from './submitTheCharacterActionForm.js';

export function castGMSpell(spellCatalogIdValue, spellLevelValue, spellDurationValue, spellCastingTimeValue) {
    let jqSpellCatalogIdTag = '#spellCatalogId';
    $(jqSpellCatalogIdTag).val(spellCatalogIdValue);

    let jqSpellLevelTag = "#spellLevel";
    $(jqSpellLevelTag).val(spellLevelValue);

    let jqSpellDurationTag = "#spellDuration";
    $(jqSpellDurationTag).val(spellDurationValue);

    let jqSpellCastingTag = "#spellCastingTime";
    $(jqSpellCastingTag).val(spellCastingTimeValue);

    submitTheCharacterActionForm('slot-action-form', 'castGMSpellCharacterAction', 'castGMSpell');
}

export function recoverSpellPoints() {
    let jqInputHoursOfSleep = $("#hoursOfSleepInput").val();
    let jqHoursOfSleepTag = "#hoursOfSleep";
    $(jqHoursOfSleepTag).val(jqInputHoursOfSleep);

    submitTheCharacterActionForm('recover-spell-points', 'recoverSpellPointsCharacterAction', 'recoverSpellPointsBySleep');
}

export function showCantrip() {
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

export function submitStopActionForm(spell_slot_id, character_action) {
    let spellslotIdTag = $("#spellSlotId");
    spellslotIdTag.val(spell_slot_id);

    submitTheCharacterActionForm("stop-action-form", "stopGMSpellCharacterAction", character_action);
}

window.castGMSpell = castGMSpell;
window.recoverSpellPoints = recoverSpellPoints;
window.showCantrip = showCantrip;
window.submitStopActionForm = submitStopActionForm;
