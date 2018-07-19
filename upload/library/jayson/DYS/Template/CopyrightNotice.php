<?php
class jayson_DYS_Template_CopyrightNotice {
    public static function copyrightNotice(array $matches) {
        $opts = XenForo_Application::get('options');
        if ($opts->jayson_dys_copyright) {
            return $matches[0] . 
                '<xen:if is="!{$jayson_crn}">' .
                '<xen:set var="$jayson_crn">1</xen:set>' .
                '</xen:if>';
        }
        return $matches[0] .
            '<xen:if is="!{$jayson_crn}">' .
            '<xen:set var="$jayson_crn">1</xen:set>' .
            '<br/>Some Modificatations by <a href="https://jaysonfong.me/" title="Jayson Fong" target="_blank">Jayson Fong</a>' .
            '</xen:if>';
    }
}




