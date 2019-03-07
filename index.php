<?php
namespace App\WordpressAPIPlugins\CustomPostType;

/**
 * Plugin Name: Passion Custom Post Types
 * Description: This is a basic custom post types initializer. This will extend later after some refactoring
 * Version: 1.0.0
 * Author: Keith Murphy
 * Author URI: http://nomadmystics.com
 * License: GPL2
 *
 * @Class CustomPostTypes
 * @package wordpress-plugin
 */


/**
 * @author Keith Murphy || nomadmystics@gamil.com
 * @description Autoload classes in this is plugin
 * @param String $class_name
 * @return void
 */
function custom_post_autoloader(String $class_name):void {
    if (false !== strpos( $class_name, 'Custom')) {
        $class_name = str_replace('\\', '/', $class_name);
        $namespace = str_replace("\\","/",__NAMESPACE__);
        $classes_dir = realpath(plugin_dir_path( __FILE__)) . DIRECTORY_SEPARATOR . 'src/php';
        $class_file = str_replace($namespace, '', $class_name) . '.php';
        $required_class = "$classes_dir/$class_file";
        require_once($required_class);
    }
}
spl_autoload_register('App\WordpressAPIPlugins\CustomPostType\custom_post_autoloader');

/**
 * @author Keith Murphy || nomadmystics@gamil.com
 * @description Autoload classes in this is plugin
 * @return void
 */
function custom_post_init():void
{
    if (class_exists('App\WordpressAPIPlugins\CustomPostType\CustomPostTypes')) {
        $customPostTypes = new CustomPostTypes();
        $customPostTypes->init();
    }

    if (class_exists('App\WordpressAPIPlugins\CustomPostType\CustomPostTaxonomies')) {
        $customPostTaxonomies = new CustomPostTaxonomies();
        $customPostTaxonomies->init();
    }
}
add_action('plugins_loaded', 'App\WordpressAPIPlugins\CustomPostType\custom_post_init');
