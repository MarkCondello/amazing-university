<!-- events listing template for event posts in the past, only accessible from link on all events page -->
<?php get_header();  
pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.',
    )
);
?>
<div class="container container--narrow page-section">
 <?php
    $today = date('Ymd');
    $past_events = new WP_Query(array(
    //check the url to set the value of paged results, default is 1 if nothing is there
    'paged'=> get_query_var('paged', 1),
    //'posts_per_page' => 1,
    'post_type' => 'event',
    'meta_key' => 'event_date', //ACF field date value
    'order_by' => 'meta_value_num',
    'order' => 'ASC',
    //only show posts from the past, not the future
    'meta_query' => array(
        array(
            'key' => 'event_date',
            'compare' => '<=',
            'value' => $today,
            'type' => 'numeric'
            )
        )
    ));
  /*  //Check out the WP_Query object below to understand how the get_query_var() method works:
  //paged (int) – number of page. Show the posts that would normally show up just on page X when using the “Older Entries” link. Read more here: https://developer.wordpress.org/reference/classes/wp_query/
  echo "<pre>";
    print_r($past_events);
 */
while($past_events->have_posts()){
    $past_events->the_post(); 
    get_template_part('template-parts/content', 'event');
}
//paginate links only works with WP default query not with WP_Query();
//we can add an array of arguments to specify the pagination we need.
echo paginate_links(array(
    'total' => $past_events->max_num_pages, //number of pages with paginations
));
//the results of this pagination do not work with the custom query either, the WP_Query argument required is 'paged'=> NUMBER OF PAGES TO PAGINATE
?>
</div>
<?php get_footer();  ?>


 