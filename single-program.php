<!-- single program post template -->
<?php  
get_header();  
while(have_posts( )) {
    the_post(); 
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
            <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"><?= the_title(); ?></h1>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo  get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> <span class="metabox__main"><?php the_title(); ?> </span></p>
        </div>
        <div class="generic-content">    
             <?= the_content(); ?>
        </div>
    <?php 
        // get the related event associated with this program which is selected in the acf field within  Event posts
            $today = date('Ymd');
            $homePageEvents = new WP_Query(array(
              'posts_per_page' => 2,
              'post_type' => 'event',
              'meta_key' => 'event_date', //ACF field date value
              'order_by' => 'meta_value_num',
              'order' => 'ASC',
              //only show posts in the future, not the past
              'meta_query' => array(
                  //filter by the date
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric'
                ),
                //filter by related programs which have the ID 
                array(
                    'key' => 'related_programs', //ACF field we setup in events
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
                )
              )
            ));

            if($homePageEvents->have_posts()) :
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() .' Events</h2>';
            while($homePageEvents->have_posts()){
              $homePageEvents->the_post(); ?>
 
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
                  <p><?php
                    if(has_excerpt()){
                      echo get_the_excerpt();
                    } else {
                      echo wp_trim_words(get_the_content(), 18);
                    }
                  ?>
                  <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                </div>
              </div>
            <?php
            }
        endif;
          ?>
    </div>
<?php }
get_footer();  
?>