export function submitTheCharacterActionForm(formId, characterActionId, characterActionValue) {
    let theCharacterActionTag =  $('#' + characterActionId);
    theCharacterActionTag.val(characterActionValue);

    
    let theForm = $('#' + formId);
    theForm.submit();
}
