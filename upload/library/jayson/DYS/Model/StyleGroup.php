<?php
class jayson_DYS_Model_StyleGroup extends XenForo_Model {
	public function getStyleGroup($userId) {
        $user = $this->_getUserModel()->getFullUserById($userId);
        return $user['jayson_displaygroup_id'];
	}
	public function setStyleGroup($displayGroup, $userId) {
        $this->_getDb()->query('UPDATE xf_user SET jayson_displaygroup_id = ? WHERE user_id = ?', array($displayGroup, $userId));
	}
    protected function _getUserModel() {
        return $this->getModelFromCache('XenForo_Model_User');
    }
}
