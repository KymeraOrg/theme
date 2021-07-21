<?php

// Generates semantic classes for BODY element
function sandbox_body_class( $print = true ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$c = array('wordpress');

	// Applies the time- and date-based classes (below) to BODY element
	sandbox_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			sandbox_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get('paged') ) || ( $page = $wp_query->get('page') ) ) && $page > 1 ) {
		// Thanks to Prentiss Riddle, twitter.com/pzriddle, for the security fix below.
		$page = intval($page); // Ensures that an integer (not some dangerous script) is passed for the variable
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Generates semantic classes for each post DIV element
function sandbox_post_class( $print = true ) {
	global $post, $sandbox_post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array( 'hentry', "p$sandbox_post_alt", $post->post_type, $post->post_status );

	// Author for the post queried
	$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));

	// Category for the post queried
	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	// Tags for the post queried; if not tagged, use .untagged
	if ( get_the_tags() == null ) {
		$c[] = 'untagged';
	} else {
		foreach ( (array) get_the_tags() as $tag )
			$c[] = 'tag-' . $tag->slug;
	}

	// For password-protected posts
	if ( $post->post_password )
		$c[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	sandbox_date_classes( mysql2date( 'U', $post->post_date ), $c );

	// If it's the other to the every, then add 'alt' class
	if ( ++$sandbox_post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join( ' ', apply_filters( 'post_class', $c ) ); // Available filter: post_class

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$sandbox_post_alt = 1;

// Generates semantic classes for each comment LI element
function sandbox_comment_class( $print = true ) {
	global $comment, $post, $sandbox_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array( $comment->comment_type );

	// Counts trackbacks (t[n]) or comments (c[n])
	if ( $comment->comment_type == 'comment' ) {
		$c[] = "c$sandbox_comment_alt";
	} else {
		$c[] = "t$sandbox_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);
		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = 'byuser comment-author-' . sanitize_title_with_dashes(strtolower( $user->user_login ));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	sandbox_date_classes( mysql2date( 'U', $comment->comment_date ), $c, 'c-' );
	if ( ++$sandbox_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join( ' ', apply_filters( 'comment_class', $c ) ); // Available filter: comment_class

	// Tada again!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function sandbox_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function sandbox_cats_meow($glue) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset($cats[$i]);
			break;
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function sandbox_tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;

	return trim(join( $glue, $tags ));
}

// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

function compile_less(){
	//Compiles the less styles to css
	require get_template_directory() . "/inc/lessc.inc.php";
	$parser = new lessc();
	$less_code = file_get_contents( get_template_directory() . "/styles.less"); // Grab LESS
	$css_code = file_get_contents( get_template_directory() . "/style.css"); // Grab CSS
	$parser->setPreserveComments(true);
	try {
		//try to build the code, if this doesn't work the admin will see a error.
		$processed_css = $parser->parse($less_code); // Process to CSS
		file_put_contents( get_template_directory() . "/style.css", $processed_css); // Write CSS
	} catch (exception $e) {
	  echo "fatal error: " . $e->getMessage();
	}
}

add_action('wp_head', 'less');
function less(){
	//checks if the user is the admin, if so, then it compiles the CSS
	if(current_user_can( 'manage_options' )){
		compile_less();
	}
}

//cron function for generating less css
function mega_less( ) {
	compile_less();
}
add_action( 'mega_less', 'mega_less' );

//Generates less/css every day
function less_cron() {
	if ( ! wp_next_scheduled( 'mega_less' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'daily', 'mega_less' );
	}
}
add_action( 'wp', 'less_cron' );

//Theme Scripts
function mega_theme_scripts() {
		wp_enqueue_style( 'style-name', get_stylesheet_uri() );
		wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/a9f7d3705c.js', array(), '1.0.0', true );
		wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.5.1.min.js', array(), '1.0.0', true );
		wp_enqueue_style( 'fonts-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans', false );
		//wp_enqueue_style( 'block-styles',  get_template_directory_uri() . '/blocks.css' );

		//lity lightbox use "data-lity attr" https://sorgalla.com/lity/
		wp_enqueue_style( 'lity', get_template_directory_uri() . '/plugins/lity/lity.css');
		wp_enqueue_script( 'lity-js', get_template_directory_uri() . '/plugins/lity/lity.js', array(), '1.0.0', true );

		//slick slider https://kenwheeler.github.io/slick/
		wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/plugins/slick/slick-theme.css');
		wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/plugins/slick/slick.css');
		wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/plugins/slick/slick.js', array(), '1.0.0', true );

}
add_action( 'wp_enqueue_scripts', 'mega_theme_scripts' );



//Menu
function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

//Start Sidebars
if ( function_exists('register_sidebar') ) {
register_sidebar(array(
	'name' => 'Menu',
	'id' => 'menu-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
));
}

if ( function_exists('register_sidebar') ) {
register_sidebar(array(
	'name' => 'Page Title',
	'id' => 'page-title-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
));
}

if ( function_exists('register_sidebar') ) {
register_sidebar(array(
	'name' => 'Sidebar',
	'id' => 'main-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
));
}

if ( function_exists('register_sidebar') ) {
register_sidebar(array(
	'name' => 'Footer',
	'id' => 'footer',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
));
}

function mytheme_post_thumbnails() {
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'mytheme_post_thumbnails' );

/*
//Example shortcode
function logo_shortcode() {
    include 'shortcode/logo.php';
}
add_shortcode('logo', 'logo_shortcode');
*/

function year_shortcode() {
  $year = date('Y');
  return $year;
}
add_shortcode('year', 'year_shortcode');

add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );


//Add Class for Page Builder
function pagebuilder_post_class( $classes ) {
    global $post;

    // consider empty content the WP editor
    if ( empty( $post ) ) {
        $is_pagebuilder_content = false;
    } else {
        $panels_data = get_post_meta( $post->ID, 'panels_data', true );
        $is_pagebuilder_content = ( empty( $panels_data ) ) ? false : true;
    }

    // add class to post_class()
    $classes[] = ( $is_pagebuilder_content ) ? 'editor-pagebuilder' : 'editor-tinymce';

    return $classes;
}
add_filter( 'post_class', 'pagebuilder_post_class' );

add_filter( 'gform_validation_message', function ( $message, $form ) {
    if ( gf_upgrade()->get_submissions_block() ) {
        return $message;
    }

    $message = "<div role='alert' aria-live='assertive' class='validation_error'><p aria-live='polite'>There was a problem with your submission. Errors have been highlighted below.</p>";
    $message .= '<ul>';

    foreach ( $form['fields'] as $field ) {

        if ( $field->failed_validation ) {
            $message .= sprintf( '<li><a href="#field_'. $form['id'] . "_" . $field->id .'" aria-describedby="#field_'. $form['id'] . "_" . $field->id .'">%s - %s</a></li>', GFCommon::get_label( $field ), $field->validation_message );
        }
    }

    $message .= '</ul></div>';


    return $message;
}, 10, 2 );

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );

//Add WooCommerce Support
add_theme_support( 'woocommerce' );

// Title Widget (For Displaying the title)
class wpb_widget extends WP_Widget {

	function __construct() {
	parent::__construct(
	// Base ID of your widget
	'wpb_widget',

	// Widget name will appear in UI
	__('Title Widget', 'wpb_widget_domain'),

	// Widget description
	array( 'description' => __( 'Use this widget to display the title of your page/post', 'wpb_widget_domain' ), )
	);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $args['after_title'];

		// This is where you run the code and display the output
		include('inc/func_title.php');

		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
		}
		else {
		$title = __( '', 'wpb_widget_domain' );
		}
		// Widget admin form
		 echo '<p>This widget has no configurations.</p>';
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

//Auto install plugins

/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.2
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/plugin-activation.php';

add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_theme_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'               => 'Header Widget', // The plugin name.
			'slug'               => 'header-widget', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/plugins/header-widget.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'               => 'Gravity Forms', // The plugin name.
			'slug'               => 'gravityforms', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/plugins/gravityforms.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),



		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		array(
			'name'      => 'Ultimate Addons for SiteOrigin',
			'slug'      => 'addon-so-widgets-bundle',
			'required'  => false,
		),
		array(
			'name'      => 'Page Builder by SiteOrigin',
			'slug'      => 'siteorigin-panels',
			'required'  => false,
		),
		array(
			'name'      => 'SiteOrigin Widgets Bundle',
			'slug'      => 'addon-so-widgets-bundle',
			'required'  => false,
		),
		array(
			'name'      => 'Max Mega Menu',
			'slug'      => 'megamenu',
			'required'  => false,
		),
		array(
			'name'      => 'Black Studio TinyMCE Widget',
			'slug'      => 'black-studio-tinymce-widget',
			'required'  => false,
		),
		array(
			'name'      => 'SiteOrigin Widgets Bundle',
			'slug'      => 'so-widgets-bundle',
			'required'  => false,
		),
		array(
			'name'      => 'Yoast SEO',
			'slug'      => 'wordpress-seo',
			'required'  => false,
		),
		array(
			'name'      => 'Classic Editor',
			'slug'      => 'classic-editor',
			'required'  => true,
		),
		array(
			'name'      => 'Enhanced Text Widget',
			'slug'      => 'enhanced-text-widget',
			'required'  => false,
		),
		array(
			'name'      => 'Advanced Custom Fields',
			'slug'      => 'advanced-custom-fields',
			'required'  => false,
		),
		array(
			'name'      => 'Custom Post Type UI',
			'slug'      => 'custom-post-type-ui',
			'required'  => false,
		),
		array(
			'name'      => 'Styles & Layouts for Gravity Forms – Best Gravity Forms Styler, Designer',
			'slug'      => 'styles-and-layouts-for-gravity-forms',
			'required'  => false,
		),


	);

	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => false,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
