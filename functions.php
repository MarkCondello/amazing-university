<?php
function university_files() {
    wp_enqueue_script('google_maps_scripts',  '//maps.googleapis.com/maps/api/js?key=AIzaSyDGuO_eDH5fSneJ9dv2U9r3pdUdY_IBoBA', null, '1.0', true );

    wp_enqueue_script('university_main_scripts', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );

    wp_enqueue_style('custom_google_fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime() );
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');

    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'university_features');

//was not working in the mu_plugin directory
function university_post_types(){
    register_post_type('campus', array(
        'supports' => array('title', 'editor', 'excerpt'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'campuses'),
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'menu_icon' => 'dashicons-location-alt' 
    ));

    register_post_type('event', array(
        'supports' => array('title', 'editor', 'excerpt'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'events'),
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar' 
    ));

    register_post_type('program', array(
        'supports' => array('title', 'editor' ),
        'has_archive' => true,
        'rewrite' => array('slug' => 'programs'),
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more' 
    ));
} 

add_action('init', 'university_post_types');  

//custom query for the events page posts to display only future events
function university_adjust_queries($query){
    //only on front end, not admin, is an program post type and is not a sub query
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query() ) {
        $query->set('orderby','title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    //only on front end, not admin, is an event post type and is not a sub query
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query() ) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date'); //ACF field date value
        $query->set('order_by','meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
            )
        );
    }
    // echo "<pre>";
    // print_r($query);
}
//before WP queries the posts in the database
add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
    $api['key'] = 'AIzaSyDGuO_eDH5fSneJ9dv2U9r3pdUdY_IBoBA';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey')

?>