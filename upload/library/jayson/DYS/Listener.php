<?php
class jayson_DYS_Listener {
	public static function Listen($class, array &$extend) {
		$extend[] = 'jayson_DYS_Extend_'.$class;
	}
}
