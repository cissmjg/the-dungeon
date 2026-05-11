export const DEFAULT_CLOSED_ICON_CLASS = "fa-chevron-down";
export const DEFAULT_OPEN_ICON_CLASS = "fa-chevron-up";

export function attributeDetailPanelClick(attributePanelId, attributePanelIconId, closedIconClass, openIconClass) {
    let jqAttributePanel = $('#' + attributePanelId);
    let jqAttributePanelIcon = $('#' + attributePanelIconId);
    
    if (jqAttributePanel.is(":hidden")) {
        jqAttributePanel.show();
    } else {
        jqAttributePanel.hide();
    }
    
    if (jqAttributePanelIcon.hasClass(closedIconClass)) {
        jqAttributePanelIcon
            .removeClass(closedIconClass)
            .addClass(openIconClass);
    } else {
        jqAttributePanelIcon
            .removeClass(openIconClass)
            .addClass(closedIconClass);
    }
}

export function rmChevronClick(attributePanelId, attributePanelIconId, closedIconClass, openIconClass) {
    attributeDetailPanelClick(attributePanelId, attributePanelIconId, closedIconClass, openIconClass);
}

export function arrowTypeChange(arrowSelectorElementId, mediumRangeElementId, mediumSwiftwingRangeElementId, longRangeElementId, longSwiftwingRangeElementId) {
    let jqArrowSelectorElement = $('#' + arrowSelectorElementId);
    let jqMediumRangeElement = $('#' + mediumRangeElementId);
    let jqMediumSwiftwingRangeElement = $('#' + mediumSwiftwingRangeElementId);
    let jqLongRangeElement = $('#' + longRangeElementId);
    let jqLongSwiftwingElement = $('#' + longSwiftwingRangeElementId);

    let arrowType = jqArrowSelectorElement.val();
    switch(arrowType) {
        case "Blunt":
            jqMediumRangeElement.show();
            jqLongRangeElement.hide();
            jqMediumSwiftwingRangeElement.hide();
            jqLongSwiftwingElement.hide();
            break;
        case "Swiftwing":
            jqMediumRangeElement.hide();
            jqLongRangeElement.hide();
            jqMediumSwiftwingRangeElement.show();
            jqLongSwiftwingElement.show();
            break;
        default:
            jqMediumRangeElement.show();
            jqLongRangeElement.show();
            jqMediumSwiftwingRangeElement.hide();
            jqLongSwiftwingElement.hide();
            break;
    }
}

export function slingAmmoChange(ammoSelectorElementId, shortRangeElementId, mediumRangeElementId, longRangeElementId) {
    const SLING_BULLET = "Bullet";
    const SLING_BULLET_DAMAGE = "2d4/2d4+1";
    const SLING_BULLET_SHORT_RANGE = "10";
    const SLING_BULLET_MEDIUM_RANGE = "11 - 20";
    const SLING_BULLET_LONG_RANGE = "21 - 40";

    const SLING_STONE = "Stone";
    const SLING_STONE_DAMAGE = "d8/2d4";
    const SLING_STONE_SHORT_RANGE = "8";
    const SLING_STONE_MEDIUM_RANGE = "9 - 16";
    const SLING_STONE_LONG_RANGE = "17 - 32";

    let jqAmmoSelectorElement = $('#' + ammoSelectorElementId);
    let jqShortRangeElement = $('#' + shortRangeElementId);
    let jqMediumRangeElement = $('#' + mediumRangeElementId);
    let jqLongRangeElement = $('#' + longRangeElementId);

    const jqAmmoType = jqAmmoSelectorElement.val();
    if (jqAmmoType == SLING_BULLET) {
        jqShortRangeElement.text(SLING_BULLET_SHORT_RANGE);
        jqMediumRangeElement.text(SLING_BULLET_MEDIUM_RANGE);
        jqLongRangeElement.text(SLING_BULLET_LONG_RANGE);
    }

    if (jqAmmoType == SLING_STONE) {
        jqShortRangeElement.text(SLING_STONE_SHORT_RANGE);
        jqMediumRangeElement.text(SLING_STONE_MEDIUM_RANGE);
        jqLongRangeElement.text(SLING_STONE_LONG_RANGE);
    }
}

window.DEFAULT_CLOSED_ICON_CLASS = DEFAULT_CLOSED_ICON_CLASS;
window.DEFAULT_OPEN_ICON_CLASS = DEFAULT_OPEN_ICON_CLASS;
window.attributeDetailPanelClick = attributeDetailPanelClick;
window.rmChevronClick = rmChevronClick;
window.arrowTypeChange = arrowTypeChange;
window.slingAmmoChange = slingAmmoChange;