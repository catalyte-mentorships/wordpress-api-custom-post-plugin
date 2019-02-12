<?php
namespace App\MuPlugins\CustomPostType;

/**
 * Class CustomPostTypesAdmin
 * @see https://medium.freecodecamp.org/how-to-create-a-wordpress-plugin-for-your-web-app-5c31733f3a9d
 * @package App\MuPlugins\CustomPostType
 */
class CustomPostTypesAdmin
{
    /**
     * @var String $textdomain
     */
    private $textdomain;

    /**
     * @var string
     */
    private $plugin_src_url;

    /**
     * @var string
     */
    private $menu_slug;

    /**
     * @var false|int
     */
    private $id;

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * CustomPostTypesAdmin constructor.
     */
    public function __construct()
    {
        $this->textdomain = 'museum';
        $this->plugin_src_url = plugins_url('/', dirname(__FILE__));
        $this->menu_slug = 'custom-post-types-admin';
        $this->id = get_the_ID();
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Call your actions and filters here
     * @return void
     */
    public function init()
    {
        add_action('admin_menu', [&$this, 'add_admin_menu']);
        add_action('wp_ajax_store_admin_data', [&$this, 'store_admin_data']);
        add_action('admin_enqueue_scripts', [&$this, 'add_admin_scripts']);
        add_action('add_meta_boxes', [&$this, 'custom_post_type_add_metaboxes']);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Add our plugin to the admin screen
     * @return void
     */
    public function add_admin_menu():void
    {

        $custom_post_types_page = add_menu_page(
            __('Custom Post Types', $this->textdomain),
            __('Custom Post Types', $this->textdomain),
            'manage_options',
            $this->menu_slug,
            [&$this, 'custom_post_types_admin_layout'],
//            [&$this, 'custom_post_type_add_metaboxes'],
            'dashicons-admin-plugins',
            10000
        );

        if (!empty($custom_post_types_page)) {

        }


    }

    public function store_admin_data():void
    {

    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Add our plugin scripts
     * @return void
     */
    public function add_admin_scripts():void
    {
        wp_enqueue_script('custom-post-types-admin', "http://192.168.10.10/app/mu-plugins/custom-post-types/build/bundle.js", [], '', true);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description On initialization of the custom post type admin screen display custom post types created/create new/delete
     * @return void
     */
    public function custom_post_types_admin_layout():void
    {
        // $views_slug = "{$this->plugin_src_url}views";
        // Warning: require_once(): php_network_getaddresses: getaddrinfo failed:
        // Temporary failure in name resolution in /home/vagrant/learning/php_projects/natural_history/web/wp/wp-includes/template.php on line 688
        // load_template(plugins_url('/', dirname(__FILE__)) . 'views/add-post-type-template.php');
        // @TODO this is not ideal, but with using vagrant I was getting error:
        load_template('http://192.168.10.10/app/mu-plugins/custom-post-types/src/views/add-post-type-template.php');
    }


    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Add metaboxes to the custom post admin page
     * @see https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
     * @return void
     */
    public function custom_post_type_add_metaboxes():void
    {
//        global $hook_suffix;
        $screen = get_current_screen();
        var_dump($screen);
        var_dump($screen->id);
        echo $screen->id;
        add_meta_box(
            'wporg_box_id', // Unique ID
            'Add New Custom Post', // Box title
            [&$this, 'custom_post_type_html'], // Content callback, must be of type callable
            $screen // Post type
        );

        do_meta_boxes($screen, 'normal', '');
//        do_action('add_meta_boxes', $screen);

        ?>
            <h2>Settings Meta Box</h2>
        <?php


    }

    public function custom_post_type_html($post):void
    {
        var_dump($post);
        var_dump('testing this is called');
//        echo "<label for=\"wporg_field\">Description for this field</label>";
        ?>
        <label for="wporg_field">Description for this field</label>
        <select name="wporg_field" id="wporg_field" class="postbox">
            <option value="">Select something...</option>
            <option value="something">Something</option>
            <option value="else">Else</option>
        </select>
        <?php

        // @see https://codex.wordpress.org/Function_Reference/submit_button
        $text = 'Add Post Type';
        $type = 'primary';
        $name = 'submit';
        $wrap = false;
        $other_attributes = null;
        submit_button( $text, $type, $name, $wrap, $other_attributes );
    }


    /**
     * @return string
     */
    protected function get_admin_page_id():string
    {
        return 'toplevel_page_custom-post-types-admin';
    }
}
