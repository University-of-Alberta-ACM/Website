<?php
/**
 * ACM functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage ACM
 * @since ACM 1.0
 */
// Load configurations.
require_once get_template_directory() . '/src/Tgm.php';
require_once get_template_directory() . '/src/Acf.php';
require_once get_template_directory() . '/src/ACMUtils.php';
require_once get_template_directory() . '/src/ACMOpenTOC.php';
require_once get_template_directory() . '/src/ACMThemeConfiguration.php';
require_once get_template_directory() . '/src/widgets/SelectTypeOfSite/SelectTypeOfSite.php';
require_once get_template_directory() . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
/**
 * ACMTheme
 */
class ACMTheme {
	/**
	 * Theme setup
	 */
	public static function init() {
		// Add inital actions.
		add_action( 'wp_head', [ __CLASS__, 'acm_javascript_detection' ] , 0 );
		add_action( 'tgmpa_register', [ __CLASS__, 'acm_require_plugins' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'acm_scripts' ] );
		add_action( 'admin_print_styles', [ __CLASS__, 'acm_admin_scripts' ] );
		add_action( 'do_meta_boxes', [ __CLASS__, 'remove_custom_field_meta_box' ] );
		add_action( 'customize_register', [ __CLASS__, 'acm_customize_register' ] );
		add_action( 'customize_preview_init', [ __CLASS__, 'social_links_customizer' ] );
		add_filter( 'wp_nav_menu_items', [ __CLASS__, 'add_seach_form' ], 10, 2 );
		add_action( 'widgets_init', [ __CLASS__, 'acm_widgets_init' ] );
		add_action( 'wp_dashboard_setup', [ 'SelectTypeOfSite', 'init' ] );
		add_action( 'pre_get_posts', [ __CLASS__, 'display_homepage_posts' ] );
		add_action( 'after_switch_theme', [ __CLASS__, 'setup_theme' ] );
		add_filter( 'the_content', [ __CLASS__, 'acm_the_content_filter' ] );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5
		 */
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]);
		// Home thumbnails.
		if ( function_exists( 'add_image_size' ) ) {
			add_theme_support( 'post-thumbnails' );
		}
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'home-excerpt-thumbnail', 327, 208, true );
		}
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus([
			'primary'	=> __( 'Primary Menu', 'acm' ),
			'secondary'	=> __( 'Secondary Menu', 'acm' ),
			'topsmall'	=> __( 'Top Small', 'acm' ),
		]);
		// Create custom options.
		if ( function_exists( 'acf_add_options_page' ) ) {
			self::add_acf_option_pages();
		}
		// Check for updates.
		// Replace dummy url for real url after create json file.
		$update_checker = Puc_v4_Factory::buildUpdateChecker(
			'http://campus.acm.org/public/infodir/wp-versions/details.json',
			__FILE__,
			'acm_update'
		);
		$updates = $update_checker->getUpdate();
		if ( ! empty( $updates ) ) {
			add_action( 'all_admin_notices', [ __CLASS__, 'acm_update_alert' ] );
		}
	}
	/**
	 * To activate CPT Single page.
	 *
	 * @author  Bainternet
	 * @link http://en.bainternet.info/2011/custom-post-type-getting-404-on-permalinks
	 * ---
	 */
	public static function setup_theme() {
		// Rewrite rules for custom posts.
		flush_rewrite_rules( false );
	}
	/**
	 * Filter for the_content.
	 *
	 * @param string $content Content from the db.
	 * @return string Content filtered.
	 */
	public static function acm_the_content_filter( $content ) {
		if ( 'conferences' === $GLOBALS['post']->post_type ) {
			return html_entity_decode( $content );
		}
		return $content;
	}
	/**
	 * Require plugins with tgm.
	 */
	public static function acm_require_plugins() {
		$plugins = [
			[
				'name'     => 'Advanced Custom Fields',
				'slug'     => 'advanced-custom-fields',
				'required' => true,
			],
			[
				'name'     => 'WP Migrate DB',
				'slug'     => 'wp-migrate-db',
				'required' => false,
			],
			[
				'name'     => 'The Events Calendar',
				'slug'     => 'the-events-calendar',
				'required' => false,
			],
			[
				'name'     => 'Appointment Booking and Online Scheduling Plugin by vCita',
				'slug'     => 'meeting-scheduler-by-vcita',
				'required' => false,
			],
			[
				'name'     => 'Meta Slider',
				'slug'     => 'ml-slider',
				'required' => false,
			],
			[
				'name'     => 'Video Gallery – YouTube Gallery',
				'slug'     => 'gallery-videos',
				'required' => false,
			],
			[
				'name'     => 'Yoast SEO',
				'slug'     => 'wordpress-seo',
				'required' => false,
			],
		];
		$config = [
			'id'           => 'ACM-Theme',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => true,
		];
		tgmpa( $plugins, $config );
	}
	/**
	 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
	 */
	public static function acm_javascript_detection() {
		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	}
	/**
	 * Add acm fonts.
	 *
	 * @return string New url
	 */
	public static function acm_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/*
		 * Translators: If there are characters in your language that are not supported
		 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'acm' ) ) {
			$fonts[] = 'Noto Sans:400italic,700italic,400,700';
		}

		/*
		 * Translators: If there are characters in your language that are not supported
		 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'acm' ) ) {
			$fonts[] = 'Noto Serif:400italic,700italic,400,700';
		}

		/*
		 * Translators: If there are characters in your language that are not supported
		 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'acm' ) ) {
			$fonts[] = 'Inconsolata:400,700';
		}

		/*
		 * Translators: To add an additional character subset specific to your language,
		 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate
		 * into your own language.
		 */
		$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'acm' );

		if ( 'cyrillic' === $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' === $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'devanagari' === $subset ) {
			$subsets .= ',devanagari';
		} elseif ( 'vietnamese' === $subset ) {
			$subsets .= ',vietnamese';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg([
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			], 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
	/**
	 * Enqueue scripts and styles
	 */
	public static function acm_scripts() {
		$path_style = get_template_directory() . '/style.css';
		// Add custom fonts, used in the main stylesheet.
		wp_enqueue_style( 'acm-fonts', self::acm_fonts_url(), [], null );
		// Load our main stylesheet.
		wp_enqueue_style( 'acm-style', get_stylesheet_uri(), [], filemtime( $path_style ) );
		// Load FontAwesome font.
		wp_enqueue_style(
			'font-awesome',
			'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'
		);
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_register_script( 'jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', null, null, true );
		wp_enqueue_script( 'modernizr-script', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js' );
		$location = '/patterns/static/js/main.js';
		$path = get_template_directory() . $location;
		if ( file_exists( $path ) ) {
			$src = get_template_directory_uri() . $location;
			wp_enqueue_script( 'acm-main', $src, [ 'jquery' ], filemtime( $path ), true );
		}
		wp_enqueue_script( 'acm-script', get_template_directory_uri() . '/js/acm.js', null, null, true );
		wp_localize_script( 'acm-script', 'screenReaderText', [
			'expand' 	=> '<span class="screen-reader-text">' . __( 'expand child menu', 'acm' ) . '</span>',
			'collapse'  => '<span class="screen-reader-text">' . __( 'collapse child menu', 'acm' ) . '</span>',
		]);
	}
	/**
	 * Enqueue scripts and styles for admin area.
	 */
	public static function acm_admin_scripts() {
		// Add custom admin scripts.
		if ( is_admin() ) {
			wp_enqueue_style( 'acm-admin', get_template_directory_uri() . '/css/admin.css' );
			wp_enqueue_style(
				'jquery-confirm',
				'//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css'
			);
			wp_enqueue_script(
				'jquery-confirm',
				'//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js',
				[ 'jquery' ],
				false,
				true
			);
		}
	}
	/**
	 * Remove meta box.
	 */
	public static function remove_custom_field_meta_box() {
		remove_meta_box( 'postcustom', 'post', 'normal' );
	}
	/**
	 * Customize register.
	 *
	 * @param WP_Customize $wp_customize Customize object reference.
	 */
	public static function acm_customize_register( $wp_customize ) {
		$wp_customize->add_section( 'banner_settings_section', [
		'title' => __( 'Banner Image', 'acm' ),
		'priority' => 30,
		]);

		$wp_customize->add_setting( 'banner_bgimage' );

		$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'banner_bgimage', [
		'label' 	=> __( 'Banner Background Image', 'acm' ),
		'section' 	=> 'banner_settings_section',
		'settings' 	=> 'banner_bgimage',
		]));

		$wp_customize->add_setting( 'banner_title', [
		'default' => '',
		] );
		$wp_customize->add_setting( 'banner_top_title', [
		'default' => '',
		] );
		$wp_customize->add_setting( 'banner_description', [
		'default' => '',
		] );

		$wp_customize->add_control( 'banner_title', [
		'label' 	=> __( 'Title', 'acm' ),
		'section'	=> 'banner_settings_section',
		'settings'	=> 'banner_title',
		'type'		=> 'text',
		]);

		$wp_customize->add_control( 'banner_top_title', [
		'label' 	=> __( 'Top Title', 'acm' ),
		'section' 	=> 'banner_settings_section',
		'settings'	=> 'banner_top_title',
		'type' 		=> 'text',
		]);

		$wp_customize->add_control( 'banner_description', [
		'label' 	=> __( 'Description', 'acm' ),
		'section' 	=> 'banner_settings_section',
		'settings'	=> 'banner_description',
		'type' 		=> 'textarea',
		]);
		// Add logo.
		$wp_customize->add_setting( 'logo_image' );
		$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'logo_image', [
		'label' 	=> __( 'Logo Image', 'acm' ),
		'section' 	=> 'title_tagline',
		'settings'	=> 'logo_image',
		]));
		// Footer logo.
		$wp_customize->add_setting( 'footer_logo' );
		$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'footer_logo', [
		'label' 	=> __( 'Footer Logo', 'acm' ),
		'section' 	=> 'title_tagline',
		'settings'	=> 'footer_logo',
		]));
	}
	/**
	 * Enqueue social script.
	 */
	public static function social_links_customizer() {
		wp_enqueue_script( 'social-links-customizer', get_template_directory_uri() . '/social-links-customizer.js', [ 'jquery' ] );
	}
	/**
	 * Add search form to menu.
	 *
	 * @param string $items Menu items.
	 * @param string $args  Menu items with search form.
	 */
	public static function add_seach_form( $items, $args ) {
		if ( 'secondary' === $args->theme_location ) {
			$items .= '
				<li class="hide-for-small">
					<form class="acm-search-form" id="form_1" action="' . get_site_url() . '" method="get">
						<input type="text" name="s" class="acm-searchbox-input" required="required" autocomplete="off" id="input_1"/>
						<label for="search-site_1" class="toggle">
							<i class="fa fa-search left"></i>
							<input type="submit" class="acm-searchbox-submit left" value="Search" name="search-site_1" id="search-site_1" />
						</label>
						<script>window.liveSearchUrl = "/live-search";</script>
					</form>
				</li>';
		}
		return $items;
	}
	/**
	 * Register sidebars and widgetized areas.
	 */
	public static function acm_widgets_init() {
		register_sidebar([
			'name'			=> 'Content sidebar',
			'id'			=> 'content_right',
			'before_widget' => '<div>',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2>',
			'after_title' 	=> '</h2>',
		]);
		register_sidebar([
			'name'			=> 'Content footer',
			'id'			=> 'content_footer',
			'before_widget' => '<div>',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2>',
			'after_title' 	=> '</h2>',
		]);
	}
	/**
	 * Modify post to display in homepage.
	 *
	 * @param object $query Query object.
	 */
	public static function display_homepage_posts( $query ) {
		if ( is_home() && $query->is_main_query() ) {
			$number_of_post_field = get_option( 'acm_number_of_posts' );
			$posts_to_display = is_numeric( $number_of_post_field ) ? $number_of_post_field : 20;
			// Display post if display in home option is setting in true.
			$meta_query = [
				'relation' => 'OR',
				[
					'key'		=> 'display_post_in_main_page',
					'value' 	=> true,
					'compare'	=> '=',
				],
				[
					'key' => 'display_post_in_main_page',
					'compare' => 'NOT EXISTS',
				],
			];
			$query->set( 'meta_query', $meta_query );
			$query->set( 'posts_per_page', $posts_to_display );
		}
	}
	/**
	 * Display alert message for new theme updates.
	 */
	public static function acm_update_alert() {
	?>
		<div class="notice notice-info is-dismissible">
			<p>A new version of the ACM Theme is available. <a href="/wp-admin/update-core.php">Click here to download it.</a></p>
		</div>
	<?php
	}
};
// Instantiate theme.
ACMTheme::init();
// Active Open TOC functionality.
ACMOpenTOC::init();
// Activate ACF fields.
ACMAcf::init();
// Activate ACM Theme Configuration page.
ACMThemeConfiguration::init();
