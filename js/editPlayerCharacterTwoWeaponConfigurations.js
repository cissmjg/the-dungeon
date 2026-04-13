
export function submitDeleteTwoWeaponConfigForm(formId, twoWeaponConfigurationElementId, twoWeaponConfigurationId) {
    if (confirm("Are you sure you want to delete the selected Two Weapon Configuration") == false) {
        return false;
    }
    
    let jqForm = $('#' + formId);
    let jqTwoWeaponConfigurationElement = $('#' + twoWeaponConfigurationElementId);
    jqTwoWeaponConfigurationElement.val(twoWeaponConfigurationId);

    jqForm.submit();
}

export function submitAddTwoWeaponConfigForm(formId, weapon1ElementId, weapon2ElementId) {
    let jqForm = $('#' + formId);
    let jqWeapon1Element = $('#' + weapon1ElementId);
    let jqWeapon2Element = $('#' + weapon2ElementId);

    if (jqWeapon1Element.val() == 0) {
        alert('Please select a mainhand weapon for Two Weapon Fighting');
        return false;
    }

    if (jqWeapon2Element.val() == 0) {
        alert('Please select a offhand weapon for Two Weapon Fighting');
        return false;
    }

    if (jqWeapon1Element.val() == jqWeapon2Element.val()) {
        alert ('Please select two DIFFERENT weapons');
        return false;
    }

    jqForm.submit();
}

export function updateWeaponId(weaponListId, weaponElementId) {
    let weaponList = $('#' + weaponListId);
    let weaponElement = $('#' + weaponElementId);

    let selectedWeaponId = weaponList.val();
    weaponElement.val(selectedWeaponId);
}

window.submitDeleteTwoWeaponConfigForm = submitDeleteTwoWeaponConfigForm;
window.submitAddTwoWeaponConfigForm = submitAddTwoWeaponConfigForm;
window.updateWeaponId = updateWeaponId;
