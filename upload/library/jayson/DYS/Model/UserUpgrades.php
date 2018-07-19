<?php
class jayson_DYS_Model_UserUpgrades extends XenForo_Model {
    public function getAllUserUpgradeTitles() {
        return $this->_getDb()->fetchPairs('
            SELECT user_upgrade_id, title
            FROM xf_user_upgrade
            ORDER BY title
        ');
    }
}