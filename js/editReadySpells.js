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

export function submitUpdateSlotForm(update_slot_form_id, spell_slot_element_id, spell_slot_id, skill_catalog_element_id, skill_catalog_select_id, character_class_name_element_id, character_class_name, spell_level_element_id, spell_level) {
    let jqUpdateSlotForm = $('#' + update_slot_form_id);
    let jqSpellSlotElement = $('#' + spell_slot_element_id);
    let jqSkillCatalogElement = $('#' + skill_catalog_element_id);
    let jqSkillCatalogSelectElement = $('#' + skill_catalog_select_id);
    let jqCharacterClassElement = $('#' + character_class_name_element_id);
    let jqSpellLevelElement = $('#' + spell_level_element_id);

    jqSpellSlotElement.val(spell_slot_id);
    jqSkillCatalogElement.val(jqSkillCatalogSelectElement.val());
    jqCharacterClassElement.val(character_class_name);
    jqSpellLevelElement.val(spell_level);
    jqUpdateSlotForm.submit();
}

export function submitReclaimCantripsForm(reclaim_cantrips_form_id, spell_slot_element_id, spell_slot_id) {
    let jqSpellSlotElement = $('#' + spell_slot_element_id);
    let jqReclaimCantripsForm = $('#' + reclaim_cantrips_form_id);

    jqSpellSlotElement.val(spell_slot_id);
    jqReclaimCantripsForm.submit();
}

export function submitCastSpellForm(cast_spell_form_id, spell_duration_element_id, spell_duration, spell_casting_time_element_id, spell_casting_time, spell_slot_id_element_id, spell_slot_id) {
    let jqCastSpellForm = $('#' + cast_spell_form_id);
    let jqSpellDurationElement = $('#' + spell_duration_element_id);
    let jqSpellCastingTimeElement = $('#' + spell_casting_time_element_id);
    let jqSpellSlotElement = $('#' + spell_slot_id_element_id);

    jqSpellDurationElement.val(spell_duration);
    jqSpellCastingTimeElement.val(spell_casting_time);
    jqSpellSlotElement.val(spell_slot_id);

    jqCastSpellForm.submit();
}

export function submitRefreshSpellSlotForm(refresh_form_id, spell_slot_id_element_id, spell_slot_id) {
    let jqRefreshSlotForm = $('#' + refresh_form_id);
    let jqSpellSlotIdElement = $('#' + spell_slot_id_element_id);

    jqSpellSlotIdElement.val(spell_slot_id);
    jqRefreshSlotForm.submit();
}

export function submitCancelRunningSpellSlotForm(running_spell_form_id, spell_slot_id_element_id, spell_slot_id) {
    let jqCancelRunningSpellForm = $('#' + running_spell_form_id);
    let jqSpellSlotIdElement = $('#' + spell_slot_id_element_id);

    jqSpellSlotIdElement.val(spell_slot_id);
    jqCancelRunningSpellForm.submit();
 }

 export function submitCancelCastingSpellSlotForm(casting_spell_form_id, spell_slot_id_element_id, spell_slot_id) {
    let jqCancelCastingSpellForm = $('#' + casting_spell_form_id);
    let jqSpellSlotIdElement = $('#' + spell_slot_id_element_id);

    jqSpellSlotIdElement.val(spell_slot_id);
    jqCancelCastingSpellForm.submit();
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
window.submitUpdateSlotForm = submitUpdateSlotForm;
window.submitReclaimCantripsForm = submitReclaimCantripsForm;
window.submitCastSpellForm = submitCastSpellForm;
window.submitRefreshSpellSlotForm = submitRefreshSpellSlotForm;
window.submitCancelRunningSpellSlotForm = submitCancelRunningSpellSlotForm;
window.submitCancelCastingSpellSlotForm = submitCancelCastingSpellSlotForm;