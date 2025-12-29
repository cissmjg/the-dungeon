function submitTheForm(formId, characterActionId, characterActionValue) {
    let theCharacterActionTag =  document.getElementById(characterActionId);
    if (theCharacterActionTag != null) {
        theCharacterActionTag.value = characterActionValue;
    } else {
        alert("Character action tag: " + characterActionId + " with ID [" + characterActionId + "] not found");
        return false;
    }

    let theForm = document.getElementById(formId);
    if (theForm != null) {
        theForm.submit();
    } else {
        alert("Form: " + formId + " not found");
    }
}

function updateExistingSpellSlot(existingSpellSlotFormId, newSpellSlotId, formId, characterActionId, characterActionValue) {
    let existingSpellSlot = document.getElementById(existingSpellSlotFormId);
    if (existingSpellSlot == null) {
        alert ("Existing spell slot with ID " + existingSpellSlotFormId + " not found");
        return false;
    }
    existingSpellSlot.value = newSpellSlotId;
    submitTheForm(formId, characterActionId, characterActionValue);
}
