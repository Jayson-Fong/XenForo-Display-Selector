<?php
class jayson_DYS_Extend_XenForo_ControllerAdmin_User extends XFCP_jayson_DYS_Extend_XenForo_ControllerAdmin_User {
    public function actionExtra() {
        $parent = parent::actionExtra();
        $userId = $parent->params['user']['user_id'];
        $userModel = $this->getModelFromCache('XenForo_Model_User');
        $user = $userModel->getUserById($userId);
        $userGroups = explode(',', $user['secondary_group_ids']);
        $userGroups[] = $user['user_group_id'];
        $groups = $this->_getUserGroupModel()->getAllUserGroups();
        $selectableGroups = array();
        $opts = XenForo_Application::get('options');
        if (!$opts->jayson_dys_alternativeview) {
            foreach ($groups as $group_id => $group) {
                if ((!isset($opts->jayson_dys_nonselectable[$group_id])) && (!empty($group['username_css']) || !$opts->jayson_dys_emptyvoid)) {
                    if (!in_array($group_id, $userGroups)) {
                        $group['title'] .= '*'; 
                    }
                    $selectableGroups[$group_id] = $group;
                }
            }
        } else {
            foreach ($groups as $group_id => $group) {
                if ((!isset($opts->jayson_dys_nonselectable[$group_id])) && (!empty($group['username_css']) || !$opts->jayson_dys_emptyvoid)) {
                    $group['title'] = $user['username'];
                    if (!in_array($group_id, $userGroups)) {
                        $group['title'] .= '*'; 
                    }
                    $selectableGroups[$group_id] = $group;
                }
            }
        }
        $parent->params['userDisplayStyleGroupId'] = $user['display_style_group_id'];
        $parent->params['selectableGroups'] = $selectableGroups;
        return $parent;
    }
    public function actionSave() {
        $parent = parent::actionSave();
        $userId = $this->_input->filterSingle('user_id', XenForo_Input::UINT);
        $styleGroupId = $this->_input->filterSingle('selected_display_group_id', XenForo_Input::UINT);
        if (empty($styleGroupId)) {
            return $parent;
        }
        $this->_getStyleGroupModel()->setStyleGroup($styleGroupId, $userId);
        $userWriter = $this->_getUserWriter();
        $user = $this->_getUserModel()->getFullUserById($userId);
        $userWriter->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, true);
        $userWriter->setExistingData($user);
        $userWriter->set('display_style_group_id', $styleGroupId);
        $userWriter->save();
        return $parent;
    }
    protected function _getStyleGroupModel() {
        return $this->getModelFromCache('jayson_DYS_Model_StyleGroup');
    }
    protected function _getUserModel() {
        return $this->getModelFromCache('XenForo_Model_User');
    }
    protected function _getUserWriter() {
        return XenForo_DataWriter::create('XenForo_DataWriter_User');
    }
    protected function _getUserGroupModel() {
        return $this->getModelFromCache('XenForo_Model_UserGroup');
    }
}
