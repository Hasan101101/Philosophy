<?php

require_once(get_theme_file_path('/inc/tgm.php'));
require_once(get_theme_file_path('/inc/attachments.php'));

if (!isset($content_width)) $content_width = 960;

if (site_url() == 'http://philosophy.local') {
   define('VERSION', time());
} else {
   define('VERSION', wp_get_theme()->get('Version'));
}

function philosophy_setup()
{
   load_theme_textdomain('philosophy');
   add_theme_support('post-thumbnails');
   add_theme_support('custom-logo');
   add_theme_support('title-tag');
   add_theme_support( 'automatic-feed-links' );
   add_theme_support('html5', array('search-form', 'comment-list'));
   add_theme_support('post-formats', array('image', 'gallery', 'quote', 'link', 'audio', 'video'));
   add_editor_style('/assets/css/editor-style.css');
   register_nav_menu('mainmenu', __('Main Menu', 'philosophy'));
   register_nav_menus(array(
      'footer-left'   => __('Footer Left Menu', 'philosophy'),
      'footer-middle' => __('Footer Middle Menu', 'philosophy'),
      'footer-right'  => __('Footer Right Menu', 'philosophy'),
   ));
   add_image_size('philosophy-home-square', 400, 400, true);
}
add_action('after_setup_theme', 'philosophy_setup');

function philosophy_assets()
{
   wp_enqueue_style('font-awesome-style', get_theme_file_uri('/assets/css/font-awesome/css/font-awesome.css'), null, '1.0');
   wp_enqueue_style('fonts-style', get_theme_file_uri('/assets/css/fonts.css'), null, '1.0');
   wp_enqueue_style('base-style', get_theme_file_uri('/assets/css/base.css'), null, '1.0');
   wp_enqueue_style('vendor-style', get_theme_file_uri('/assets/css/vendor.css'), null, '1.0');
   wp_enqueue_style('main-style', get_theme_file_uri('/assets/css/main.css'), null, '1.0');
   wp_enqueue_style('philosophy-style', get_stylesheet_uri(), null, VERSION);

   wp_enqueue_script('modernizr', get_theme_file_uri('/assets/js/modernizr.js'), null, '1.0');
   wp_enqueue_script('pace', get_theme_file_uri('/assets/js/pace.min.js'), null, '1.0');
   wp_enqueue_script('plugins', get_theme_file_uri('/assets/js/plugins.js'), array('jquery'), '1.0', true);
   if (is_singular()) {
      wp_enqueue_script("comment-reply");
   };
   wp_enqueue_script('main', get_theme_file_uri('/assets/js/main.js'), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'philosophy_assets');

function philosophy_pagination()
{
   global $wp_query;
   $links = paginate_links(array(
      'current'      => max(1, get_query_var('paged')),
      'total'        => $wp_query->max_num_pages,
      'type'         => 'list',
      'mid_size'     => 3,
   ));
   $links = str_replace('page-numbers', 'pgn__num', $links);
   $links = str_replace("<ul class='pgn__num'>", "<ul>", $links);
   $links = str_replace('next pgn__num', 'pgn__next', $links);
   $links = str_replace('prev pgn__num', 'pgn__prev', $links);
   echo wp_kses_post($links);
}

// To remove auto generate p tag
remove_action('term_description', 'wpautop');

function philosophy_widgets()
{
   register_sidebar(array(
      'name'          => __('About Us Page', 'philosophy'),
      'id'            => 'about-us',
      'description'   => __('Widgets in this area will be shown on about us page.', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="col-block %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="quarter-top-margin">',
      'after_title'   => '</h3>',
   ));

   register_sidebar(array(
      'name'          => __('Contact Page Maps Section', 'philosophy'),
      'id'            => 'contact-maps',
      'description'   => __('Widgets in this area will be shown on contact page.', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="%2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '',
      'after_title'   => '',
   ));

   register_sidebar(array(
      'name'          => __('Contact Page Information Section', 'philosophy'),
      'id'            => 'contact-info',
      'description'   => __('Widgets in this area will be shown on contact page.', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="col-block %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="quarter-top-margin">',
      'after_title'   => '</h3>',
   ));

   register_sidebar(array(
      'name'          => __('Before Footer Section', 'philosophy'),
      'id'            => 'before-footer-right',
      'description'   => __('Before Footer Section, Right Side', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="%2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3>',
      'after_title'   => '</h3>',
   ));

   register_sidebar(array(
      'name'          => __('Footer Section', 'philosophy'),
      'id'            => 'footer-right',
      'description'   => __('Footer Section, Right Side', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="%2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
   ));

   register_sidebar(array(
      'name'          => __('Footer Bottom Section', 'philosophy'),
      'id'            => 'footer-bottom',
      'description'   => __('Footer Section, Bottom Side', 'philosophy'),
      'before_widget' => '<div id="%1$s" class="%2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '',
      'after_title'   => '',
   ));

   register_sidebar(array(
      'name'          => __('Header Section', 'philosophy'),
      'id'            => 'header-section',
      'description'   => __('Widgets in this area will be shown on Header Section.', 'philosophy'),
      'before_widget' => '<ul id="%1$s" class="header__social %2$s">',
      'after_widget'  => '</ul>',
      'before_title'  => '',
      'after_title'   => '',
   ));
}
add_action('widgets_init', 'philosophy_widgets');

function philosophy_search_form($form)
{
   $home_dir = home_url('/');
   $label = __('Search For:', 'philosophy');
   $btn_label = __('Search:', 'philosophy');
   $newform = <<<FORM
      <form role="search" method="get" class="header__search-form" action="{$home_dir}">
         <label>
            <span class="hide-content">{$label}</span>
            <input type="search" class="search-field" placeholder="Type Keywords" value="" name="s" title="{$label}" autocomplete="off">
         </label>
         <input type="submit" class="search-submit" value="{$btn_label}">
      </form>
   FORM;

   return $newform;
}
add_filter('get_search_form', 'philosophy_search_form');
