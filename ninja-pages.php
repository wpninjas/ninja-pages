<?php
/*
Plugin Name: Ninja Pages
Plugin URI: http://plugins.wpninjas.net/?p=72
Description: A simple plugin that allows the user to assign categories and tags to pages.
Version: 1.4.2
Author: The WP Ninjas
Author URI: http://www.wpninjas.net
*/

/**
 * Define constants
 **/
define('NINJA_PAGES_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
define('NINJA_PAGES_URL', plugins_url() .'/' . basename( dirname( __FILE__ ) ) );

/**
 * Include core files
 **/
require_once( NINJA_PAGES_DIR . '/basic-functions.php' );

function ninja_pages_load_lang() {
	$lang_dir = NINJA_PAGES_DIR . 'lang/';
	load_plugin_textdomain( 'ninja-pages', false, $lang_dir );
}

if ( is_admin() ) {
	add_action('admin_menu', 'ninja_pages_create_settings_menu');
	add_action('admin_init', 'ninja_pages_add_cats_box');
	add_action('admin_init', 'ninja_pages_add_tags_box');
	add_action('admin_init', 'ninja_pages_add_excerpt_box');
	// add the admin settings and such
	add_action('admin_init', 'ninja_pages_admin_init');
} else {
	add_action('init', 'ninja_pages_init');
	$options = get_option('ninja_pages_options');
	if( isset( $options['display_children'] ) ) :
		add_filter( 'the_content', 'ninjapages_child_pages' );
	endif;
}

function ninja_pages_admin_init(){
	include( NINJA_PAGES_DIR . '/basic-settings.php' );
	wp_register_style( 'ninja_pages_admin_css', NINJA_PAGES_URL . '/css/ninja_pages_admin.css' );
	if ( !current_theme_supports( 'post-thumbnails' ) ) {
		//add_theme_support( 'post-thumbnails' );
		//echo 'test';
		//die();
	}
}

function ninja_pages_init(){
	if ( !current_theme_supports( 'post-thumbnails' ) ) {
		//add_theme_support( 'post-thumbnails' );
		//echo 'test';
		//die();
	}
	include( NINJA_PAGES_DIR . '/shortcodes.php' );
	add_action('init', 'ninja_pages_load_lang');
	add_action('after_setup_theme', 'ninja_pages_post_thumbnails');
}

function ninja_pages_create_settings_menu() {
	$page = add_options_page(
		'Ninja Pages',
		'Ninja Pages',
		'manage_options',
		'ninja-pages',
		'ninja_pages_plugin_options'
	);
	add_action( 'admin_print_styles-' . $page, 'ninja_pages_admin_styles' );
}

function ninja_pages_admin_styles() {
       wp_enqueue_style( 'ninja_pages_admin_css' );
   }

function ninja_pages_plugin_options() {

	global $wpdb;

	?>
	<div class="wrap">
		<h1><?php _e( 'Ninja Pages', 'ninja_pages' ); ?></h1>
		<div class="wrap-left">
		<form method="post" action="options.php">
			<?php settings_fields( 'ninja_pages_options' ); ?>
			<?php do_settings_sections( 'ninja_pages_options' ); ?>
			<br />
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'ninja-pages' ); ?>" />
		</form>

		<?php //do_settings_sections( 'ninja_pages_details' ); ?>
		</div>
		<div class="wrap-right">
			<a href="wp-admin/plugin-install.php?tab=search&s=Ninja+Forms"><img src="<?php echo NINJA_PAGES_URL;?>/images/nf-banner-240x400.png" width="240px" height="400px" /></a>
<!-- 			<ul>
				<li><a href="http://wpninjas.net/?p=562" target="_blank">Ninja Forms</a></li>
				<li><a href="http://wpninjas.net/?p=1131" target="_blank">Ninja Announcements</a></li>
			</ul> -->
		</div>
	</div>


<?php
}
add_action('after_setup_theme', 'ninja_pages_post_thumbnails');
function ninja_pages_post_thumbnails() {
	if ( !current_theme_supports( 'post-thumbnails' ) ) {
		add_theme_support( 'post-thumbnails' );
	}
}
