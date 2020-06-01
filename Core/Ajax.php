<?php
namespace Concise;

class Ajax 
{
    public static function setHandler($action, $callback, $adminOnly=false)
    {
        add_action("wp_ajax_{$action}", $callback);
        
        if (!$adminOnly) {
            add_action("wp_ajax_nopriv_{$action}", $callback);
        }
    }
}
