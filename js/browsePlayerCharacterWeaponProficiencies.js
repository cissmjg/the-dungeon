
export function submitAddWeaponProficiencyForm(formId, weaponProficiencyElementId, weaponProficiencyId) {
    let jqForm = $('#' + formId);
    let jqWeaponProficiencyElement = $('#' + weaponProficiencyElementId);
    jqWeaponProficiencyElement.val(weaponProficiencyId);

    jqForm.submit();
}

window.submitAddWeaponProficiencyForm = submitAddWeaponProficiencyForm;
