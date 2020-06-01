<?php
namespace Concise;

use \Ancillary;

class Template 
{   
    public $viewPath;
    
    
    
    
    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
    }
    
    
    
    
    
    public static function setFlash($key, $data)
    {
        $cookieName = "flash_message_{$key}";
        $cookieData = urlencode(http_build_query($data));
        
        if (!headers_sent()) {
            setcookie($cookieName, $cookieData, 0, "/");
        } else {
            echo "
                <script> 
                    var expires = new Date(new Date().setTime(new Date().getTime() + (7*24*60*60*1000))).toUTCString();
                    document.cookie='{$cookieName}={$cookieData}; expires=' + expires + '; path=/'
                </script>";
        }        
    }
    
    public static function getFlash($key)
    {
        $data = null;
        $cookieName = "flash_message_{$key}";
        
        if (isset($_COOKIE['flash_message_' . $key])) {
            parse_str(urldecode($_COOKIE['flash_message_' . $key]), $data);
            
            if (!headers_sent()) {
                setcookie($cookieName, "", time()-3600, "/");
            } else {
                echo "<script> document.cookie='{$cookieName}=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/'; </script>";
            } 
        }
        
        return $data;
    }
    
    
    
    
    
    public function render($view, array $vars=[], $viewPath=null)
    {
        $viewPath = (is_null($viewPath) ? $this->viewPath : $viewPath);
        
        extract($vars);    
        ob_start();
        require "{$viewPath}/{$view}.php";
        
        return ob_get_clean();
    }
}
