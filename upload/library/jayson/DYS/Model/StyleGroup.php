<?php
class jayson_DYS_Model_StyleGroup extends XenForo_Model {
	public function getStyleGroup($userId) {
        $user = $this->_getUserModel()->getFullUserById($userId);
        return $user['jayson_displaygroup_id'];
	}
	public function setStyleGroup($displayGroup, $userId) {
        $user = $this->_getUserModel()->getFullUserById($userId);
        $userWriter = $this->_getUserWriter();
        $userWriter->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, true);
        $userWriter->setExistingData($user);
        $userWriter->set('jayson_displaygroup_id', $displayGroup);
        $userWriter->save();
	}
    protected function _getUserWriter() {
        return XenForo_DataWriter::create('XenForo_DataWriter_User');
    }
    protected function _getUserModel() {
        return $this->getModelFromCache('XenForo_Model_User');
    }
}
