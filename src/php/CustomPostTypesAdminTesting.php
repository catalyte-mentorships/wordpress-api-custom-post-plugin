<?php
namespace App\MuPlugins\CustomPostType;

/**
 * Class CustomPostTypesAdmin
 * @see https://medium.freecodecamp.org/how-to-create-a-wordpress-plugin-for-your-web-app-5c31733f3a9d
 * @package App\MuPlugins\CustomPostType
 */
class CustomPostTypesAdminTesting
{
    /* === SETTINGS PAGE === */


    /* Add Settings Page */
    private $add_post_type;
    private $custom_post_types;

    /**
     * CustomPostTypesAdminTesting constructor.
     */
    public function __construct()
    {
        var_dump('testing');
        $this->custom_post_types = 'custom_post_types';
        $this->add_post_type = 'add_post_type';
    }

    public function init()
    {
        add_action( 'admin_menu', [&$this, 'fx_smb_settings_setup'] );
        /* Add Meta Box */
        add_action( 'add_meta_boxes', [&$this, 'fx_smb_submit_add_meta_box'] );
        /* Add Meta Box */
        add_action( 'add_meta_boxes', [&$this, 'fx_smb_basic_add_meta_box'] );
    }


    /**
     * Create Settings Page
     * @since 0.1.0
     * @see https://shellcreeper.com/wp-settings-meta-box/
     * @uses fx_smb_setings_page_id()
     */
    public function fx_smb_settings_setup(){

        /* Register our setting. */
        register_setting(
            'fx_smb',                         /* Option Group */
            'fx_smb_basic',                   /* Option Name */
            [&$this, 'fx_smb_basic_sanitize']           /* Sanitize Callback */
        );

        /* Add settings menu page */
        $settings_page = add_menu_page(
            'f(x) Settings Meta Box Example', /* Page Title */
            'Meta Box Testing',                       /* Menu Title */
            'manage_options',                 /* Capability */
            'fx_smb',                         /* Page Slug */
            [&$this, 'fx_smb_settings_page'],           /* Settings Page Function Callback */
            'dashicons-align-left',           /* Menu Icon */
            5                                 /* Menu Position */
        );

        /* Vars */
        $page_hook_id = $this->fx_smb_setings_page_id();

        /* Do stuff in settings page, such as adding scripts, etc. */
        if ( !empty( $settings_page ) ) {

            /* Load the JavaScript needed for the settings screen. */
            add_action( 'admin_enqueue_scripts', [&$this, 'fx_smb_enqueue_scripts'] );
            add_action( "admin_footer-{$page_hook_id}", [&$this, 'fx_smb_footer_scripts'] );

            /* Set number of column available. */
            add_filter( 'screen_layout_columns', [&$this, 'fx_smb_screen_layout_column'], 10, 2 );

        }
    }

    /**
     * Utility: Settings Page Hook ID
     * The Settings Page Hook, it's the same with global $hook_suffix.
     * @since 0.1.0
     */
    public function fx_smb_setings_page_id() {
        return 'toplevel_page_fx_smb';
    }


    /**
     * Settings Page Callback
     * used in fx_smb_settings_setup().
     * @since 0.1.0
     */
    public function fx_smb_settings_page() {

        /* global vars */
        global $hook_suffix;

        /* utility hook */
        do_action( 'fx_smb_settings_page_init' );

        /* enable add_meta_boxes function in this page. */
        do_action( 'add_meta_boxes', $hook_suffix );
        ?>

        <div class="wrap">

            <h2>Settings Meta Box</h2>

            <?php settings_errors(); ?>

            <?php
            // @see https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-5-tabbed-navigation-for-settings--wp-24971

            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'custom_post_types';
            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=fx_smb&tab=custom_post_types" class="nav-tab <?php echo $active_tab == $this->custom_post_types ? 'nav-tab-active' : ''; ?>">Custom Post Types</a>
                <a href="?page=fx_smb&tab=add_post_type" class="nav-tab <?php echo $active_tab == $this->add_post_type ? 'nav-tab-active' : ''; ?>">Add Post Type</a>
            </h2>

            <div class="fx-settings-meta-box-wrap">

                <form id="fx-smb-form" method="post" action="options.php">

                    <?php settings_fields( 'fx_smb' ); // options group  ?>
                    <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
                    <?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

                    <div id="poststuff">


                        <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">

                            <div id="postbox-container-1" class="postbox-container">

                                <?php
//                                if ($this->add_post_type === $active_tab) {
//                                    do_meta_boxes($hook_suffix, 'side', null);
//                                }
//                                ?>
                                <!-- #side-sortables -->

                            </div><!-- #postbox-container-1 -->

                            <div id="postbox-container-2" class="postbox-container">
                                <?php
                                if ($this->add_post_type === $active_tab) {
                                    do_meta_boxes( $hook_suffix, 'normal', null );
                                }
                                ?>
                                <!-- #normal-sortables -->

                                <?php do_meta_boxes( $hook_suffix, 'advanced', null ); ?>
                                <!-- #advanced-sortables -->

                            </div><!-- #postbox-container-2 -->

                        </div><!-- #post-body -->

                        <br class="clear">

                    </div><!-- #poststuff -->

                </form>

            </div><!-- .fx-settings-meta-box-wrap -->

        </div><!-- .wrap -->
        <?php
    }


    /**
     * Load Script Needed For Meta Box
     * @since 0.1.0
     */
    public function fx_smb_enqueue_scripts( $hook_suffix)  {
        $page_hook_id = $this->fx_smb_setings_page_id();
        if ( $hook_suffix == $page_hook_id ) {
            wp_enqueue_script( 'common' );
            wp_enqueue_script( 'wp-lists' );
            wp_enqueue_script( 'postbox' );
            wp_enqueue_script('custom-post-types-scripts', dirname(__FILE__) . '../../../build/bundle.js', [], false, true);
        }
    }

    /**
     * Footer Script Needed for Meta Box:
     * - Meta Box Toggle.
     * @since 0.1.0
     */
    public function fx_smb_footer_scripts(){
        $page_hook_id = $this->fx_smb_setings_page_id();
        ?>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready( function($) {
                // toggle
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                postboxes.add_postbox_toggles( '<?php echo $page_hook_id; ?>' );

                // display spinner
                $('#fx-smb-form').submit( function() {
                    $('#publishing-action .spinner').css('display','inline');
                });
            });
            //]]>
        </script>
<!--        not ideal but works-->
        <script src="'http://192.168.10.10/app/mu-plugins/custom-post-types/build/bundle.js"></script>
        <?php
    }

    /**
     * Number of Column available in Settings Page.
     * we can only set to 1 or 2 column.
     * @since 0.1.0
     */
    public function fx_smb_screen_layout_column( $columns, $screen ) {
        $page_hook_id = $this->fx_smb_setings_page_id();
        if ( $screen == $page_hook_id )
            $columns[$page_hook_id] = 2;
        return $columns;
    }


    /* === SUBMIT / SAVE META BOX === */



    /**
     * Add Submit/Save Meta Box
     * @since 0.1.0
     * @uses fx_smb_submit_meta_box()
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     */
    public function fx_smb_submit_add_meta_box(){

        $page_hook_id = $this->fx_smb_setings_page_id();

        add_meta_box(
            'submitdiv',               /* Meta Box ID */
            'Save Options',            /* Title */
            [&$this, 'fx_smb_submit_meta_box'],  /* Function Callback */
            $page_hook_id,                /* Screen: Our Settings Page */
            'side',                    /* Context */
            'high'                     /* Priority */
        );
    }

    /**
     * Submit Meta Box Callback
     * @since 0.1.0
     */
    public function fx_smb_submit_meta_box() {

        /* Reset URL */
        $reset_url = '#';

        ?>

        <div id="submitpost" class="submitbox">

            <div id="major-publishing-actions">

<!--               <div id="delete-action">-->
<!--                    <a href="--><?php //echo esc_url( $reset_url ); ?><!--" class="submitdelete deletion">Reset Settings</a>-->
<!--                </div><!-- #delete-action -->

<!--                <div id="publishing-action">-->
<!--                    <span class="spinner"></span>-->
<!--                    --><?php //submit_button( esc_attr( 'Save' ), 'primary', 'submit', false );?>
<!--                </div>-->

                <div class="clear"></div>

            </div><!-- #major-publishing-actions -->

        </div><!-- #submitpost -->

        <?php
    }

    /* === EXAMPLE BASIC META BOX === */

    /**
     * Basic Meta Box
     * @since 0.1.0
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     */
    function fx_smb_basic_add_meta_box(){

        $page_hook_id = $this->fx_smb_setings_page_id();

        add_meta_box(
            'basic',                  /* Meta Box ID */
            'Add Custom Post Type',               /* Title */
            [&$this, 'fx_smb_basic_meta_box'],  /* Function Callback */
            $page_hook_id,               /* Screen: Our Settings Page */
            'normal',                 /* Context */
            'default'                 /* Priority */
        );
    }

    /**
     * Submit Meta Box Callback
     * @since 0.1.0
     */
    public function fx_smb_basic_meta_box() {
        ?>
        <?php /* Simple Text Input Example */ ?>
        <div class="add_custom_post_form">
            <p>
                <label for="basic-text">Singular Name:</label>
                <input id="basic-text" class="widefat" type="text" name="fx_smb_basic" value="<?php echo sanitize_text_field( get_option( 'fx_smb_basic' . '_1', '' ) );?>">
                <label for="basic-text">Plural Name:</label>
                <input id="basic-text" class="widefat" type="text" name="fx_smb_basic" value="<?php echo sanitize_text_field( get_option( 'fx_smb_basic' . '_1', '' ) );?>">
            </p>
            <p class="howto">To display this option use PHP code<code>get_option( 'fx_smb_basic' );</code>.</p>
            <div id="publishing-action">
                <span class="spinner"></span>
                <?php submit_button( esc_attr( 'Save' ), 'primary', 'submit', false );?>
            </div>
        </div>
        <?php
    }

    /**
     * Sanitize Basic Settings
     * This function is defined in register_setting().
     * @since 0.1.0
     */
    public function fx_smb_basic_sanitize( $settings  ) {
        $settings = sanitize_text_field( $settings );
        return $settings ;
    }
}
