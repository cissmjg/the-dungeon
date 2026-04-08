export function submitAddPreferredWeaponProficiencyForm(formId, preferredWeaponListId, preferredWeaponProficiencyElementId) {
    let jqForm = $('#' + formId);
    const jqPreferredWeaponList = $('#' + preferredWeaponListId);
    let preferredWeaponProficiencyElement = $('#' + preferredWeaponProficiencyElementId);

    const jqPreferredWeaponid = jqPreferredWeaponList.val();
    preferredWeaponProficiencyElement.val(jqPreferredWeaponid);
    
    jqForm.submit();
}

window.submitAddPreferredWeaponProficiencyForm = submitAddPreferredWeaponProficiencyForm;
