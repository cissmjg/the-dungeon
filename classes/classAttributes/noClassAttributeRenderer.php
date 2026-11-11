<?php

require_once 'playerCharacterSkillSet.php';
require_once 'characterDetails.php';
require_once 'attributeMetadata.php';
require_once 'rowClassManager.php';

require_once __DIR__ . '/../../helper/HtmlHelper.php';

class NoClassAttributeRenderer {

        public function render(RowClassManager $row_class_manager) {
            $background_class_name = $row_class_manager->getClassName();
            return HtmlHelper::buildSpanTagWithText($background_class_name, 'NONE');
        }
}
?>