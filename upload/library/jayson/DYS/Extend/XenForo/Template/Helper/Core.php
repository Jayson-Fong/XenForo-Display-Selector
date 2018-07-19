<?php
class jayson_DYS_Extend_XenForo_Template_Helper_Core extends XenForo_Template_Helper_Core {
	public static $displayGroupStyleIds;
	public static function helperUserNameHtml(array $user, $username = '', $rich = false, array $attributes = array()) {
		if ($username == '') {
			$username = htmlspecialchars($user['username']);
		}
		if (!array_key_exists('display_style_group_id', $user)) {
			if (!isset(self::$displayGroupStyleIds[$user['user_id']])) {
				$tpu = XenForo_Model::create('XenForo_Model_User')->getVisitingUserById($user['user_id']);
				self::$displayGroupStyleIds[$user['user_id']] = $tpu['display_style_group_id'];
			}
			$user['display_style_group_id'] = self::$displayGroupStyleIds[$user['user_id']];
		}
        if (empty($user['is_banned'])) {
		$username = self::callHelper('richusername', array($user, $username));
        }  else if (XenForo_Visitor::getInstance()->hasPermission('general', 'bypassUserPrivacy')) {
             $username = '<span class="banned">' . $username . '</span>';
        }
		$href = self::getUserHref($user, $attributes);
		$class = (empty($attributes['class']) ? '' : ' ' . htmlspecialchars($attributes['class']));
		unset($attributes['href'], $attributes['class']);
		$attribs = self::getAttributes($attributes);
		return "<a{$href} class=\"username{$class}\"{$attribs}>{$username}</a>";
	}
}