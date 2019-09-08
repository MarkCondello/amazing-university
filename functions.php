<?php
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
    if(!$args['title']):
        $args['title'] = get_the_title();
    endif;

    if(!$args['subtitle']) :
        $args['subtitle'] = get_field("page_banner_title");
    endif;

    if(!$args['photo']) :
        if(get_field("page_banner_background_image")) :
            $args['photo'] = get_field("page_banner_background_image")['sizes']['pageBanner'];
        else:
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        endif;
    endif;
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>">
         </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>  
    </div>
    <?php
}

function university_files() {
    wp_enqueue_script('google_maps_scripts',  '//maps.googleapis.com/maps/api/js?key=AIzaSyDGuO_eDH5fSneJ9dv2U9r3pdUdY_IBoBA', null, '1.0', true );

    wp_enqueue_script('university_main_scripts', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );

    wp_enqueue_style('custom_google_fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

    wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime() );

    //wordpress method for including markup in the dom related to the server which is extensible so we can add whatever properties we want in the assoicative array
    wp_localize_script('university_main_scripts', 'uniData', array(
        'root_url' => get_site_url(),
        'test' =>  'abc',
        )
    );
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');

    add_theme_support('title-tag');
    //include featured images
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

//was not working in the mu_plugin directory
function university_post_types(){
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
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
        'capability_type' => 'event',
        'map_meta_cap' => 'true',
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
        'menu_icon' => 'dashicons-lightbulb',
        'show_in_rest' => true
    ));


    register_post_type('professor', array(
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail' ),
        'public' => true,
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
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

    //only on front end, not admin, is an campus post type and is not a sub query
    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query() ) {
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
add_filter('acf/fields/google_map/api', 'universityMapKey');

//redirect to home page instead of admin if subscriber 
function redirect_subs_to_home(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == "subscriber"){
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirect_subs_to_home');

//remove to topbar admin if subscriber is logged
function remove_topbar(){
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

function ourLoginCss(){
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime() );
    wp_enqueue_style('custom_google_fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

}
add_action("login_enqueue_scripts", "ourLoginCss");

function ourLoginTitle(){
    return get_bloginfo("name");
}
add_filter("login_headertitle", "ourLoginTitle");
?>