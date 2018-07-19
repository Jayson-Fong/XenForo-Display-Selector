<?php
class jayson_DYS_Extend_XenForo_ControllerPublic_Account extends XFCP_jayson_DYS_Extend_XenForo_ControllerPublic_Account {
    public function actionPreferences() {
        $parent = parent::actionPreferences();
        if ($parent instanceof XenForo_ControllerResponse_View) {
            $visitor = XenForo_Visitor::getInstance();
            $groups = $this->_getUserGroupModel()->getAllUserGroups();
            $visitorGroups = explode(',', $visitor['secondary_group_ids']);
            $visitorGroups[] = $visitor['user_group_id'];
            $opts = XenForo_Application::get('options');
            $selectableGroups = array();
            if (!$opts->jayson_dys_alternativeview) {
                foreach ($groups as $group_id => $group) {
                    if ((($visitor->hasPermission('jayson_dys', 'canSelectAnyDisplayGroup') || in_array($group_id, $visitorGroups)) && !isset($opts->jayson_dys_nonselectable[$group_id])) && (!empty($group['username_css']) || !$opts->jayson_dys_emptyvoid)) {
                        $selectableGroups[$group_id] = $group;
                    }
                }
            } else {
                foreach ($groups as $group_id => $group) {
                    if ((($visitor->hasPermission('jayson_dys', 'canSelectAnyDisplayGroup') || in_array($group_id, $visitorGroups)) && !isset($opts->jayson_dys_nonselectable[$group_id])) && (!empty($group['username_css']) || !$opts->jayson_dys_emptyvoid)) {
                        $group['title'] = $visitor['username'];
                        $selectableGroups[$group_id] = $group;
                    }
                }
            }
            if (count($selectableGroups) > 1 && $visitor->hasPermission('jayson_dys', 'canSelectDisplayGroup')) {
                $canSelectDisplayGroup = true;
            } else {
                $canSelectDisplayGroup = false;
            }
            $parent->subView->params['selectableGroups'] = $selectableGroups;
            $parent->subView->params['canSelectDisplayGroup'] = $canSelectDisplayGroup;
        }
        return $parent;
    }
    public function actionPreferencesSave() {
        $visitor = XenForo_Visitor::getInstance();
        $opts = XenForo_Application::get('options');
        $groupId = $this->_input->filterSingle('selected_display_group_id', XenForo_Input::UINT);
        if (empty($groupId)) {
            return parent::actionPreferencesSave();
        }
        if (($this->_getUserModel()->isMemberOfUserGroup($visitor->toArray(), $groupId, true) || $visitor->hasPermission('jayson_dys', 'canSelectAnyDisplayGroup')) && !isset($opts->jayson_dys_nonselectable[$groupId])) {
            XenForo_Application::set('display_style_group_id', $groupId);
            $this->_getStyleGroupModel()->setStyleGroup($groupId, $visitor['user_id']);
        }
        return parent::actionPreferencesSave();
    }
    protected function _getStyleGroupModel() {
        return $this->getModelFromCache('jayson_DYS_Model_StyleGroup');
    }
    protected function _getUserModel() {
        return $this->getModelFromCache('XenForo_Model_User');
    }
    protected function _getUserGroupModel() {
        return $this->getModelFromCache('XenForo_Model_UserGroup');
    }
}