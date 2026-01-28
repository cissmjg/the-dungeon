import { submitTheCharacterActionForm } from './submitTheCharacterActionForm.js';

export function hideOtherElements(all_occupied_slots, submit_icon_id, cancel_icon_id, hide_element_icon_id, update_existing_slot_submit_icon_id) {

    // Hide the other spells for this level
    let otherSlots = all_occupied_slots.split(",");
    otherSlots.forEach(function (otherSlot, index) {
        let otherSlotElement = $('#' + otherSlot);
        otherSlotElement.css("opacity", "0.0");
    });

    // Hide the feather that updates unallocated slots
    let submitIcon = $('#' + submit_icon_id);
    submitIcon.hide();

    // Make the cancel icon visible
    let cancelEditIcon = $('#' + cancel_icon_id);
    cancelEditIcon.show();

    // Hide the 'hide elements' icon
    let hideElementIcon = $('#' + hide_element_icon_id);
    hideElementIcon.hide();

    // Show the feather that updates existing slots
    let updateExistingSlotSubmitIcon = $('#' + update_existing_slot_submit_icon_id);
    updateExistingSlotSubmitIcon.show();
}

export function unhideOtherElements(all_occupied_slots, submit_icon_id, cancel_icon_id, hide_element_icon_id, update_existing_slot_submit_icon_id) {

    // Show the other spells for this level
    let otherSlots = all_occupied_slots.split(",");
    otherSlots.forEach(function (otherslot, index) {
        let otherSlotElement = $('#' + other_slot);
        otherSlotElement.css("opacity", "1.0");
    });

    // Show the feather that updates unallocated slots
    let submitIcon = $('#' + submit_icon_id);
    submitIcon.show();

    // Make the cancel icon hidden
    let cancelEditIcon = $('#' + cancel_icon_id);
    cancelEditIcon.hide();

    // Show the 'hide elements' icon
    let hideElementIconId = $('#' + hide_element_icon_id);
    hideElementIconId.show();

    // Hide the feather that updates existing slots
    let updateExistingSlotSubmitIcon = $('#' + update_existing_slot_submit_icon_id);
    updateExistingSlotSubmitIcon.hide();
}

export function updateExistingSpellSlot(existingSpellSlotFormId, newSpellSlotId, formId, characterActionId, characterActionValue) {
    let existingSpellSlot = $('#' + existingSpellSlotFormId);
    existingSpellSlot.val(newSpellSlotId);
    submitTheCharacterActionForm(formId, characterActionId, characterActionValue);
}

window.submitTheCharacterActionForm = submitTheCharacterActionForm;
window.hideOtherElements = hideOtherElements;
window.unhideOtherElements = unhideOtherElements;
window.updateExistingSpellSlot = updateExistingSpellSlot;
