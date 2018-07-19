<?php
class jayson_DYS_Extend_XenForo_Model_UserUpgrade extends XFCP_jayson_DYS_Extend_XenForo_Model_UserUpgrade {
    public function upgradeUser($userId, array $upgrade, $allowInsertUnpurchasable = false, $endDate = null) {
        $parent = parent::upgradeUser($userId, $upgrade, $allowInsertUnpurchasable, $endDate);
        $opts = $this->_getOptions();
        $autoApply = $opts->jayson_dys_auto_apply;
        if (!$autoApply) {
            return $parent;
        }
        $autoApplyUpgrades = $opts->jayson_dys_auto_apply_upgrades;
        if (!isset($autoApplyUpgrades[$upgrade['user_upgrade_id']])) {
            return $parent;
        }
        $upgradeExtraGroupIds = explode(',', $upgrade['extra_group_ids']);
        foreach ($upgradeExtraGroupIds as $upgradeExtraGroupId) {
            if (!isset($opts->jayson_dys_nonselectable[$upgradeExtraGroupId])) {
                $styleGroupId = $upgradeExtraGroupId;
                $this->_getStyleGroupModel()->setStyleGroup($styleGroupId, $userId);
                $userWriter = $this->_getUserWriter();
                $user = $this->_getUserModel()->getFullUserById($userId);
                $userWriter->setOption(XenForo_DataWriter_User::OPTION_ADMIN_EDIT, true);
                $userWriter->setExistingData($user);
                $userWriter->set('display_style_group_id', $styleGroupId);
                $userWriter->save();
                break;
            }
        }
        return $parent;
    }
    protected function _getOptions() {
        return XenForo_Application::get('options');
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
}