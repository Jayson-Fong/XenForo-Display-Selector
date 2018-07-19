<?php
class jayson_DYS_Option_UserUpgrades {
    public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit) {
        $preparedOption['formatParams'] = array();
        foreach (XenForo_Model::create('jayson_DYS_Model_UserUpgrades')->getAllUserUpgradeTitles() as $upgrade_id => $upgrade_name) {
            $preparedOption['formatParams'][] = array(
                'name' => "{$fieldPrefix}[{$preparedOption['option_id']}][$upgrade_id]",
                'label' => $upgrade_name,
                'selected' => !empty($preparedOption['option_value'][$upgrade_id]),
            );
        }
        return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal('option_list_option_checkbox', $view,
            $fieldPrefix, $preparedOption, $canEdit, array(
                'class' => 'checkboxColumns',
            ));
    }
}