<?php
require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-routes.php');

//custom REST API properties
function uni_custom_rest(){
    register_rest_field('post', 'authorName', array(
        'get_callback' => function(){
            return get_the_author();
        }
    ));
    register_rest_field('post', 'featuredImg', array(
        'get_callback' => function(){
            return get_the_post_thumbnail();
        }
    ));
    register_rest_field('post', 'bannerTitle', array(
        'get_callback' => function(){
            return get_field('page_banner_title');
        }
    ));
    register_rest_field('event', 'authorName', array(
        'get_callback' => function(){
            return get_the_author();
        }
    ));
    register_rest_field('page', 'authorName', array(
        'get_callback' => function(){
            return get_the_author();
        }
    ));
    register_rest_field('note', 'notesCount', [
        'get_callback' => function(){
            return count_user_posts(get_current_user_id(), 'note');
        }
    ]);
}
add_action('rest_api_init', 'uni_custom_rest');

//my attempt at the banner function, which works
function page_banner(  $pageBannerImg, $banner_title) {
    if($pageBannerImg) :
        $pageBannerImg = $pageBannerImg['sizes']['pageBanner'];
     else :
        $pageBannerImg = get_theme_file_uri('/images/ocean.jpg');
     endif;  
     if( $banner_title) :
        $banner_title =  $banner_title;
     else :
        $banner_title = "Generic title content" ; 
     endif;

    $html = '<div class="page-banner">';
        $html .= '<div class="page-banner__bg-image" style="background-image: url(' . $pageBannerImg . ')"></div>';
        $html .= '<div class="page-banner__content container container--narrow">';
             $html .= '<h1 class="page-banner__title">' . get_the_title() . '</h1>';

             $html .= '<div class="page-banner__intro">';  
               $html .= '<p>' . $banner_title . ' </p>';
             $html .= '</div>';   
         $html .= '</div>';  
    $html .= '</div>';
    echo $html;
}

function pageBanner($args = NULL){
    if (!isset($args['title'])):
        $args['title'] = get_the_title();
    endif;
    if (!isset($args['subtitle'])):
        $args['subtitle'] = get_field("page_banner_subtitle");
    endif;
    if (!isset($args['photo']) && !is_archive() && !is_home()) :
        if (get_field("page_banner_background_image")) :
            $args['photo'] = get_field("page_banner_background_image")['sizes']['pageBanner'];
        else:
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        endif;
    endif; ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= $args['photo'] ?>"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?= $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?= $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
    <?php
}

function university_files() {
    
    wp_enqueue_script('jquery', get_theme_file_uri('/node_modules/jquery/dist/jquery.js'), null, '1.0', true );
    wp_enqueue_script('owl_carousel_scripts', get_theme_file_uri('/node_modules/owl.carousel/dist/owl.carousel.min.js'), null, '1.0', true );
    wp_enqueue_script('google_maps_scripts', '//maps.googleapis.com/maps/api/js?key=AIzaSyDGuO_eDH5fSneJ9dv2U9r3pdUdY_IBoBA', null, '1.0', true );
    wp_enqueue_script('university_main_scripts', get_theme_file_uri('/build/index.js'), null, microtime(), true );
    // wp_enqueue_script('university_main_scripts', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );
    wp_enqueue_style('custom_google_fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style('owl_carouselstyles',  get_theme_file_uri("/node_modules/owl.carousel/dist/assets/owl.carousel.min.css"), NULL, microtime() );

    wp_enqueue_style('university_reset_styles', get_theme_file_uri('/build/index.css'), NULL, microtime() );
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), NULL, microtime() );
    // wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime() );
    //wordpress method for including markup in the dom related to the server which is extensible so we can add whatever properties we want in the assoicative array
    wp_localize_script('university_main_scripts', 'uniData', [
            'root_url' => get_site_url(),
            'test' =>  'abc',
            'foo' => 'barr',
            'nonce' => wp_create_nonce('wp_rest'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); //include featured images
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query){
    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby','title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) { //custom query for the events page posts to display only future events
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
}
add_action('pre_get_posts', 'university_adjust_queries'); //before WP queries the posts in the database

function universityMapKey($api) {
    $api['key'] = 'AIzaSyDGuO_eDH5fSneJ9dv2U9r3pdUdY_IBoBA';
    return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

//redirect to home page instead of admin if users role is subscriber.
function redirect_subs_to_home(){
    if (userIsSubscriber()) {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirect_subs_to_home');

function userIsSubscriber(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == "subscriber"){
        return true;
    }
    return false;
}

function remove_topbar(){ //remove to topbar admin if subscriber is logged
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == "subscriber"){
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'remove_topbar');

function ourHeaderUrl(){
    return esc_url(site_url('/'));
}
add_filter("login_headerurl", "ourHeaderUrl");

function ourLoginCSS(){
    wp_enqueue_style('custom_google_fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_reset_styles', get_theme_file_uri('/build/index.css'), NULL, microtime() );
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'), NULL, microtime() );
}
add_action("login_enqueue_scripts", "ourLoginCSS");

function ourLoginTitle(){
    return get_bloginfo("name");
}
add_filter("login_headertext", "ourLoginTitle");

//force note posts to be private and strip special characters from being saved to notes content
function makeNotePrivate($data, $postArray){
    if ($data['post_type'] == 'note') {
        // If a post ID is not included with the request, it is a create note
        if (!$postArray['ID'] && count_user_posts(get_current_user_id(), 'note') > 4) {
            die('You have reached your note limit');
        }
        $data['post_title'] = sanitize_textarea_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }
    if ($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
        $data['post_status'] = 'private';
    }
    return $data;
}
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

// //this code reside in the mu-plugin directory
// function university_post_types(){
//     register_post_type('campus', array(
//         'map_meta_cap' => true,
//         'show_in_rest' => true,
//         'supports' => array('title', 'editor', 'excerpt'),
//         'has_archive' => true,
//         'rewrite' => array('slug' => 'campuses'),
//         'public' => true,
//         'labels' => array(
//             'name' => 'Campuses',
//             'add_new_item' => 'Add New Campus',
//             'edit_item' => 'Edit Campus',
//             'all_items' => 'All Campuses',
//             'singular_name' => 'Campus',
//         ),
//         'menu_icon' => 'dashicons-location-alt',
//     ));
//     register_post_type('event', array(
//         'map_meta_cap' => 'true',
//         'show_in_rest' => true,
//         'supports' => array('title', 'editor', 'excerpt'),
//         'has_archive' => true,
//         'rewrite' => array('slug' => 'events'),
//         'public' => true,
//         'labels' => array(
//             'name' => 'Events',
//             'add_new_item' => 'Add New Event',
//             'edit_item' => 'Edit Event',
//             'all_items' => 'All Events',
//             'singular_name' => 'Event'
//         ),
//         'menu_icon' => 'dashicons-calendar'
//     ));
//     register_post_type('program', array(
//         'show_in_rest' => true,
//         'supports' => array('title', 'editor' ),
//         'has_archive' => true,
//         'rewrite' => array('slug' => 'programs'),
//         'public' => true,
//         'labels' => array(
//             'name' => 'Programs',
//             'add_new_item' => 'Add New Program',
//             'edit_item' => 'Edit Program',
//             'all_items' => 'All Programs',
//             'singular_name' => 'Program'
//         ),
//         'menu_icon' => 'dashicons-lightbulb',
//         'show_in_rest' => true
//     ));
//     register_post_type('professor', array(
//         'show_in_rest' => true,
//         'supports' => array('title', 'editor', 'excerpt', 'thumbnail' ),
//         'public' => true,
//         'labels' => array(
//             'name' => 'Professors',
//             'add_new_item' => 'Add New Professor',
//             'edit_item' => 'Edit Professor',
//             'all_items' => 'All Professors',
//             'singular_name' => 'Professor'
//         ),
//         'menu_icon' => 'dashicons-welcome-learn-more' 
//     ));
// } 
// add_action('init', 'university_post_types', 1);

require get_theme_file_path('/includes/rating-routes.php');

?>