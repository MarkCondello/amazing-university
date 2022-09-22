<!-- events listing template for event posts in the past, only accessible from link on all events page -->
<?php get_header();
pageBanner([
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.',
]);?>
<div class="container container--narrow page-section">
<?php
$today = date('Ymd');
$past_events = new WP_Query([
'paged'=> get_query_var('paged', 1), //check the url to set the value of paged results, default is 1 if nothing is there
'post_type' => 'event',
'meta_key' => 'event_date', //ACF field date value
'order_by' => 'meta_value_num',
'order' => 'ASC',
//only show posts from the past, not the future
'meta_query' => [
        [
        'key' => 'event_date',
        'compare' => '<=',
        'value' => $today,
        'type' => 'numeric'
        ]
    ]
]);
while($past_events->have_posts()){
    $past_events->the_post(); 
    get_template_part('template-parts/content', 'event');
}
//paginate links only works with WP default query not with WP_Query(); Review video 35 from course.
//we can add an array of arguments to specify the pagination we need.
echo paginate_links(['total' => $past_events->max_num_pages, ]);
//the results of this pagination do not work with the custom query either, the WP_Query argument required is 'paged'=> NUMBER OF PAGES TO PAGINATE
?>
</div>
<?php get_footer();?>


 