<?php
class jayson_DYS_Option_UserGroups {
    public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit) {
        $preparedOption['formatParams'] = array();
        foreach (XenForo_Model::create('XenForo_Model_UserGroup')->getAllUserGroupTitles() as $group_id => $group_name) {
            $preparedOption['formatParams'][] = array(
                'name' => "{$fieldPrefix}[{$preparedOption['option_id']}][$group_id]",
                'label' => $group_name,
                'selected' => !empty($preparedOption['option_value'][$group_id]),
            );
        }
        return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal('option_list_option_checkbox', $view,
            $fieldPrefix, $preparedOption, $canEdit, array(
                'class' => 'checkboxColumns',
            ));
    }
}