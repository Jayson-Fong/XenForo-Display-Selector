<?php
class jayson_DYS_Extend_XenForo_DataWriter_User extends XFCP_jayson_DYS_Extend_XenForo_DataWriter_User {
         protected function _getFields() {
            $parent = parent::_getFields();
            $parent['xf_user']['jayson_displaygroup_id'] = array('type' => self::TYPE_UINT, 'default' => NULL);
             return $parent;
         }
        public function rebuildDisplayStyleGroupId() {
            $parent = parent::rebuildDisplayStyleGroupId();
            $styleGroupId = $this->_getStyleGroupModel()->getStyleGroup($this->get('user_id'));
            $groups = explode(',', $this->get('secondary_group_ids'));
            $groups[] = $this->get('user_group_id');
            if (empty($styleGroupId) || $this->get('is_banned')) {
                return $parent;
            }
            $user = $this->_getUserModel()->getUserById($this->get('user_id'));
            if ((!in_array($styleGroupId, $groups) && !XenForo_Permission::hasPermission($user, 'jayson_dys', 'canSelectAnyDisplayGroup')) || isset($opts->jayson_dys_nonselectable[$styleGroupId])) {
                if (!$this->getOption(self::OPTION_ADMIN_EDIT)) {
                    if ($this->_getOptions()->jayson_dys_unlock_on_noperm) {
                        $this->_getStyleGroupModel()->setStyleGroup(NULL, $this->get('user_id'));
                        return $parent;
                    }
                }
            }
            $this->_setPostSave('display_style_group_id', $styleGroupId);
            $this->_db->update('xf_user',
                array('display_style_group_id' => $styleGroupId),
                'user_id = ' . $this->_db->quote($this->get('user_id'))
            );
            return $parent;
        }
        protected function _preSave() {
            if (XenForo_Application::isRegistered('display_style_group_id')) {
                $this->set('display_style_group_id', $this->_getStyleGroupModel()->getStyleGroup($this->get('user_id')), 'xf_user');
            }
            parent::_preSave();
        }
        protected function _getStyleGroupModel() {
            return $this->getModelFromCache('jayson_DYS_Model_StyleGroup');
        }
        protected function _getUserModel() {
            return $this->getModelFromCache('XenForo_Model_User');
        }
        protected function _getOptions() {
            return XenForo_Application::get('options'); 
        }
}
