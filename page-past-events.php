<!-- events listing template for event posts in the past, only accessible from link on all events page -->
<?php get_header();  ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>">
    </div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Past Events</h1>
        <p>A recap of our past events.</p>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
        <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

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
        $past_events->the_post(); ?>
        <div class="event-summary">
        <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <?php 
            // must be Year Month Day YYYYMMDD in ACF return format to grab options using the DateTime PHP object
            $eventDate = new DateTime(get_field('event_date'));                    
            ?>
            <span class="event-summary__month"><?php echo $eventDate->format('M') ; ?></span>
            <span class="event-summary__day"><?php echo $eventDate->format('d') ; ?></span>  
        </a>
        <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
        </div>
        </div>
    <?php
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


 