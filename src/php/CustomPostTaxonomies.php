<?php

namespace App\PassionPlugins\CustomPostType;

/**
 * @author Keith Murphy || nomadmystics@gmail.com
 * Class CustomPostTaxonomies
 * @package App\PassionPlugins\CustomPostType
 */

class CustomPostTaxonomies
{
    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Call your actions and filters here
     * @return void
     */
    public function init()
    {
        add_action('init', [&$this, 'create_passion_faqs_tax']);
        add_action('init', [&$this, 'create_passion_slides_tax']);
    }

	/**
	 * @author Keith Murphy || nomadmystics@gmail.com
	 * @description Factory for creating custom taxonomies for custom post types
	 * @param String $taxonomy
	 * @param String $post_type
	 * @param String $name
	 * @param String $singular_name
	 * @param String $rewrite_slug
	 * @param String $textdomain
	 * @return void
	 */
    private function custom_tax_factory(
        String $taxonomy,
        String $post_type,
        String $name,
        String $singular_name,
        String $rewrite_slug,
        String $textdomain = 'passion'
    ):void {
        // Add new taxonomy, make it hierarchical (like categories)
        $labels = [
            'name'              => _x($name, $name, $textdomain),
            'singular_name'     => _x($singular_name, $singular_name, $textdomain),
            'search_items'      => __("Search $name", $textdomain),
            'all_items'         => __("All $name", $textdomain),
            'parent_item'       => __("Parent $singular_name", $textdomain),
            'parent_item_colon' => __("Parent $singular_name:", $textdomain),
            'edit_item'         => __("Edit $singular_name", $textdomain),
            'update_item'       => __("Update $singular_name", $textdomain),
            'add_new_item'      => __("Add New $singular_name", $textdomain),
            'new_item_name'     => __("New $singular_name Name", $textdomain),
            'menu_name'         => __($name, $textdomain),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => $rewrite_slug],
        ];

        register_taxonomy($taxonomy, $post_type, $args);
        register_taxonomy_for_object_type($taxonomy, $post_type);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Creates a custom taxonomy for Passion FAQs custom post type
     * @return void
     */
    public function create_passion_faqs_tax():void
    {
        $post_type = 'passion_faqs';
        $taxonomy = 'passion_faqs';
        $name = 'Passion FAQs Taxonomies';
        $singular_name = 'Passion FAQ Taxonomy';
		$rewrite_slug = 'passion-faqs-tax';

        $this->custom_tax_factory($taxonomy, $post_type, $name, $singular_name, $rewrite_slug);
    }

    /**
     * @author Keith Murphy || nomadmystics@gmail.com
     * @description Creates a custom taxonomy for Passion Slides custom post type
     * @return void
     */
    public function create_passion_slides_tax():void
    {
        $post_type = 'passion_slides';
        $taxonomy = 'passion_slides';
        $name = 'Passion Slides Taxonomies';
        $singular_name = 'Passion Slide Taxonomy';
		$rewrite_slug = 'passion-slides-tax';

        $this->custom_tax_factory($taxonomy, $post_type, $name, $singular_name, $rewrite_slug);
    }
}
