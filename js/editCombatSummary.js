import { attributeDetailPanelClick, rmChevronClick, DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS } from './dcs.js';

function divSwap(source_div_id, target_div_id) {
    let jqOtherWeaponContainer = $('#' + target_div_id);
    let jqCurrentWeaponContainer = $('#' + source_div_id);

    let jqOtherWeapon = jqOtherWeaponContainer.children("div").detach();
    let jqCurrentWeapon = jqCurrentWeaponContainer.children("div").detach();

    jqCurrentWeapon.appendTo(jqOtherWeaponContainer);
    jqOtherWeapon.appendTo(jqCurrentWeaponContainer);
}

export function weaponCheckboxChanged(form_id) {
    const jqFormId = '#' + form_id;
    const jqCheckboxSelector = jqFormId + " input[type='checkbox']";

    let checkboxes = $(jqCheckboxSelector);
    let checkedCheckboxes = checkboxes.filter(":checked");
    let checkedCheckboxCount = checkedCheckboxes.length;

    if (checkedCheckboxCount != 2) {
        return;
    }

    // Two DIVs to swap
    const div1ID = checkedCheckboxes[0].value;
    const div2ID = checkedCheckboxes[1].value;
    divSwap(div1ID, div2ID);

    const jqForm = $(jqFormId);
    jqForm.submit();

    // Clear the checkboxes
    $(jqCheckboxSelector).prop("checked", false);
}

window.weaponCheckboxChanged = weaponCheckboxChanged;
window.DEFAULT_CLOSED_ICON_CLASS = DEFAULT_CLOSED_ICON_CLASS;
window.DEFAULT_OPEN_ICON_CLASS = DEFAULT_OPEN_ICON_CLASS;
window.attributeDetailPanelClick = attributeDetailPanelClick;
window.rmChevronClick = rmChevronClick;
