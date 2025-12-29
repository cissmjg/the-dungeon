function deallocateExtraSlot(formId, characterActionId, characterActionValue, spellSlotIdValue) {
    let spellSlotIdElement = document.getElementById("spellSlotId");
    if (spellSlotIdElement != null) {
        spellSlotIdElement.value = spellSlotIdValue;
    } else {
        alert("Spell Slot ID tag: " + characterActionId + " with ID [spellSlotId] not found");
        return false;
    }

    submitTheForm(formId, characterActionId, characterActionValue);
}