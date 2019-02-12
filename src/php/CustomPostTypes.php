<?php
namespace App\MuPlugins\CustomPostType;

/**
 * @author Keith Murphy || nomadmystics@gmail.com
 * Class CustomPostTypes
 * @package App\MuPlugins\CustomPostType
 */
class CustomPostTypes
{
    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Call your actions and filters here
     * @return void
     */
    public function init():void
    {
        add_action('init', [&$this, 'museum_past_exhibits']);
        add_action('init', [&$this, 'museum_current_exhibits']);
        add_action('init', [&$this, 'museum_future_exhibits']);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description This uses the factory pattern to build custom post types
     * @param String $post_type
     * @param String $name
     * @param String $singular_name
     * @param Bool $is_public
     * @param Bool $has_archive
     * @param String $rewrite_slug
     * @param Int $menu_position
     * @param String $textdomain
     * @return void
     */
    private function custom_post_factory(
        String $post_type,
        String $name = '',
        String $singular_name = '',
        Bool $is_public = true,
        Bool $has_archive = true,
        String $rewrite_slug = '',
        Int $menu_position = 1,
        String $textdomain = 'museum'
    ):void {
        register_post_type($post_type,
            [
                'labels' => [
                    'name'               => _x($name, $name, $textdomain),
                    'singular_name'      => _x($singular_name, $singular_name, $textdomain),
                    'menu_name'          => _x($name, 'admin menu', $textdomain),
                    'name_admin_bar'     => _x($singular_name, 'add new on admin bar', $textdomain),
                    'add_new'            => _x('Add New', $singular_name, $textdomain),
                    'add_new_item'       => __("Add New $singular_name", $textdomain),
                    'new_item'           => __("New $singular_name", $textdomain),
                    'edit_item'          => __("Edit $singular_name", $textdomain),
                    'view_item'          => __("View $singular_name", $textdomain),
                    'all_items'          => __("All $name", $textdomain),
                    'search_items'       => __("Search $name", $textdomain),
                    'parent_item_colon'  => __("Parent $name:", $textdomain),
                    'not_found'          => __("No $name found.", $textdomain),
                    'not_found_in_trash' => __("No $name found in Trash.", $textdomain)
                ],
                'public' => $is_public,
                'has_archive' => $has_archive,
                'rewrite' => ['slug' => $rewrite_slug],
                'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats',],
                'taxonomies' => ['category'],
                'menu_position' => $menu_position,
            ]
        );
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description This will build the custom post type for Past Exhibits from the British Museum
     * @return void
     */
    public function museum_past_exhibits():void
    {
        $post_type = 'past_exhibits';
        $name = __('Past Exhibits');
        $singular_name = __('Past Exhibit');
        $is_public = true;
        $has_archive = true;
        $rewrite_slug = 'past-exhibits';
        $menu_position = 5;

        $this->custom_post_factory($post_type, $name, $singular_name, $is_public, $has_archive, $rewrite_slug, $menu_position);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description This will build the custom post type for Current Exhibits from the British Museum
     * SUBJECT TO CHANGE
     * @return void
     */
    public function museum_current_exhibits():void
    {
        $post_type = 'current_exhibits';
        $name = __('Current Exhibits');
        $singular_name = __('Current Exhibit');
        $is_public = true;
        $has_archive = true;
        $rewrite_slug = 'current-exhibits';
        $menu_position = 4;

        $this->custom_post_factory($post_type, $name, $singular_name, $is_public, $has_archive, $rewrite_slug, $menu_position);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description This will build the custom post type for Future Exhibits from the British Museum
     * SUBJECT TO CHANGE
     * @return void
     */
    public function museum_future_exhibits():void
    {
        $post_type = 'future_exhibits';
        $name = __('Future Exhibits');
        $singular_name = __('Future Exhibit');
        $is_public = true;
        $has_archive = true;
        $rewrite_slug = 'future-exhibits';
        $menu_position = 5;

        $this->custom_post_factory($post_type, $name, $singular_name, $is_public, $has_archive, $rewrite_slug, $menu_position);
    }
}
