<?php
namespace App\WordpressAPIPlugins\CustomPostType;

/**
 * @author Keith Murphy || nomadmystics@gmail.com
 * Class CustomPostTypes
 * @package App\WordpressAPIPlugins\CustomPostType
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
	    add_action('init', [&$this, 'create_api_current_exhibits_posts']);
	    add_filter('use_block_editor_for_post_type', [&$this, 'disable_block_editor'], 100, 2);
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
        String $textdomain = 'api'
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
                'show_in_rest' => true,
                'rewrite' => ['slug' => $rewrite_slug],
                'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats',],
                'taxonomies' => ['category'],
                'menu_position' => $menu_position,
            ]
        );
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description This will build the custom post type for Current Exhibits that can be called from the Wordpress API
     * @return void
     */
    public function create_api_current_exhibits_posts():void
    {
        $post_type = 'current_exhibits';
        $name = __('Current Exhibits');
        $singular_name = __('Current Exhibit');
        $is_public = true;
        $has_archive = true;
        $rewrite_slug = 'current-exhibits-post';
        $menu_position = 5;

        $this->custom_post_factory($post_type, $name, $singular_name, $is_public, $has_archive, $rewrite_slug, $menu_position);
    }

    public function disable_block_editor ($use_block_editor, $post_type)
    {
    	if ('current_exhibits' == $post_type) {
    		return false;
	    }

    	return $use_block_editor;
    }
}
