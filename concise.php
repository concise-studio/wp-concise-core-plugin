<?php
/*
Plugin Name: Concise Core Plugin
Plugin URI:  http://concise-studio.com
Description: Core plugin-lib
Version: 102
Author: Concise Studio
Author URI: http://concise-studio.com
*/

// Load core
require __DIR__ . "/Core/Libs/Ancillary.php";

spl_autoload_register(function($class) {
    // Set params
    $prefix = "Concise";
    $baseDir = __DIR__ . "/Core/";

    // Check namespace
    $length = strlen($prefix);
    
    if (strncmp($prefix, $class, $length) !== 0) {
        return;
    }

    // Get the file
    $relativeClass = substr($class, $length);
    $file = $baseDir . str_replace("\\", "/", $relativeClass) . ".php";
    
    
    // Require file
    if (file_exists($file)) {
        require $file;
    }
});




// Register JS
add_action("wp_enqueue_scripts", function() { 
    wp_register_script("concise-validation", plugins_url("concise/js/validation.js"), ["jquery"], "20170501", true);
    wp_register_script("concise-beauty-radiobuttons", plugins_url("concise/js/beauty-radiobuttons.js"), ["jquery"], "20170105", true);
    wp_register_script("concise-beauty-checkboxes", plugins_url("concise/js/beauty-checkboxes.js"), ["jquery"], "20170117", true);
});





// Set plugin first in the queue of loading plugins
add_action("activated_plugin", function() { 
    $path = str_replace(WP_PLUGIN_DIR . "/", "", __FILE__ );
    $plugins = get_option("active_plugins");
    $key = array_search($path, $plugins);
    
    array_splice($plugins, $key, 1);
    array_unshift($plugins, $path);
    update_option("active_plugins", $plugins);
});
