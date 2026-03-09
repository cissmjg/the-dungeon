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

window.DEFAULT_CLOSED_ICON_CLASS = DEFAULT_CLOSED_ICON_CLASS;
window.DEFAULT_OPEN_ICON_CLASS = DEFAULT_OPEN_ICON_CLASS;
window.attributeDetailPanelClick = attributeDetailPanelClick;