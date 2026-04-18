export function submitAddWeaponForm(formId, weaponElementId, weaponProficiencyId) {
    let jqForm = $('#' + formId);
    let jqWeaponElement = $('#' + weaponElementId);
    jqWeaponElement.val(weaponProficiencyId);

    jqForm.submit();
}

window.submitAddWeaponForm = submitAddWeaponForm;
