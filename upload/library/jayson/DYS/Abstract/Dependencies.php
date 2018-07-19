<?php
abstract class jayson_DYS_Abstract_Dependencies {
    public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data) {
        $helperCallbacks = array('usernamehtml' => array('jayson_DYS_Extend_XenForo_Template_Helper_Core', 'helperUserNameHtml'));
        XenForo_Template_Helper_Core::$helperCallbacks = array_merge(XenForo_Template_Helper_Core::$helperCallbacks, $helperCallbacks);
    }
}