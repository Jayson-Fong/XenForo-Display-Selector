<?php
class jayson_DYS_Install {
        public static function Install($existing, $addOn) {
                if (!$existing) {
                        $db = XenForo_Application::getDb();
                        $queries = array();
                        $queries['jayson_displaygroup_id'] = "ALTER TABLE `xf_user` ADD `jayson_displaygroup_id` INT NULL DEFAULT NULL AFTER `display_style_group_id`;";
                        foreach ($queries AS $id => $query) {
                                $db->query($query);
                        }
                }
        }
        public static function Uninstall() {
                XenForo_Application::getDb()->query('ALTER TABLE `xf_user` drop COLUMN `jayson_displaygroup_id` ');
        }
}
