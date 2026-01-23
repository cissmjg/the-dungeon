import { submitTheCharacterActionForm } from './submitTheCharacterActionForm.js';

export function hideSpellShowChangeForm(row_id, change_slot_id) {
    let spellRow = $('#' + row_id);
    spellRow.hide();

    let changeSlotForm = $('#' + change_slot_id);
    changeSlotForm.show();
}

export function showSpellHideChangeForm(row_id, change_slot_id) {
    let spellRow = $('#' + row_id);
    spellRow.show();

    let changeSlotForm = $('#' + change_slot_id);
    changeSlotForm.hide();
}

export function submitSlotActionForm(spell_slot_id, character_action, spell_duration, spell_casting_time) {
    let spell_slot_id_element = document.getElementById("spellSlotId");
    if (spell_slot_id_element != null) {
    } else {
        alert ("Slot Action Form 'Spell Slot ID' with ID " + spell_slot_id + " not found");
        return false;
    }
    spell_slot_id_element.value = spell_slot_id;

    let spell_duration_element = document.getElementById("spellDuration");
    if (spell_duration_element != null) {
    } else {
        alert ("Slot Action Form Spell 'Duration' not found");
        return false;
    }
    spell_duration_element.value = spell_duration;

    let spell_casting_time_element = document.getElementById("spellCastingTime");
    if (spell_casting_time_element != null) {
    } else {
        alert ("Slot Action Form Spell 'Casting Time' not found");
        return false;
    }
    spell_casting_time_element.value = spell_casting_time;

    submitTheCharacterActionForm("slot-action-form", "characterAction", character_action);
}

window.submitTheCharacterActionForm = submitTheCharacterActionForm;
window.hideSpellShowChangeForm = hideSpellShowChangeForm;
window.showSpellHideChangeForm = showSpellHideChangeForm;
window.submitSlotActionForm = submitSlotActionForm;
