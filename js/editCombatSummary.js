function arrowClick(source_div_id, target_div_id) {
    let jqOtherWeaponContainer = $('#' + target_div_id);
    let jqCurrentWeaponContainer = $('#' + source_div_id);

    let jqOtherWeapon = jqOtherWeaponContainer.children("div").detach();
    let jqCurrentWeapon = jqCurrentWeaponContainer.children("div").detach();

    jqCurrentWeapon.appendTo(jqOtherWeaponContainer);
    jqOtherWeapon.appendTo(jqCurrentWeaponContainer);
}

window.arrowClick = arrowClick;