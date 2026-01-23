import { submitTheCharacterActionForm } from './submitTheCharacterActionForm.js';

export function deallocateExtraSlot(formId, characterActionId, characterActionValue, spellSlotIdValue) {
    let spellSlotIdTag = $('#' + "spellSlotId");
    spellSlotIdTag.val(spellSlotIdValue);

    submitTheCharacterActionForm(formId, characterActionId, characterActionValue);
}

window.deallocateExtraSlot = deallocateExtraSlot;
window.submitTheCharacterActionForm = submitTheCharacterActionForm;