<!-- single campus post template -->
<?php  
get_header();  
pageBanner();
while(have_posts( )) {
    the_post();
?>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?> </span></p>
    </div>
    <div class="generic-content">    
            <?= the_content(); ?>
        <div class="acf-map">
        <?php $mapLocation = get_field("map_location");?>
            <div class="marker" data-lat ="<?=  $mapLocation['lat']; ?>" data-lng="<?= $mapLocation['lng']; ?>">
                <h3><?= the_title(); ?></h3>
            <?php echo $mapLocation['address']; 
            ?>
            </div>
        </div>
    </div>

<?php 
$relatedPrograms = new WP_Query(array(
    'posts_per_page' => -1, //all programs
    'post_type' => 'program',
    'order_by' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        //filter by related campus which have the ID 
        array(
            'key' => 'related_campus', //ACF field we setup for programs
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
        )
    )
));
//echo get_the_ID();

if($relatedPrograms->have_posts()) :
  echo '<hr class="section-break">';
  echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';
  echo '<ul class="min-list link-list">';
//gather the program Id so it can be used the subsequent related events query  
  $programId = "";
  while($relatedPrograms->have_posts()):
    $relatedPrograms->the_post(); 

    $programId = get_the_ID($relatedPrograms);
    echo "Program ID: " . $programId;
    ?>
    <li>
      <a href="<?php the_permalink();?>" ><?php the_title(); ?>       
      </a>  
    </li>
  <?php
  
  endwhile;
  wp_reset_postdata(); // reset the global post object to the url based query
endif;
  echo '</ul>';

 

//included related events on the campus
//program posts are associated to a campus with an ACF relationship field
//events posts are associated to a program through an ACF relationship field and do not directly relate to a campus

$programsFromCampus = new WP_Query(array(
    'posts_per_page' => -1, //all programs
    'post_type' => 'program',
    'order_by' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        //filter by related campus which have the ID 
        array(
            'key' => 'related_campus', //ACF field we setup for programs
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
        )
    )
));

if($programsFromCampus->have_posts()) :
    echo '<hr class="section-break">';
    echo '<h2 class="headline headline--medium">Related Events at this campus</h2>';
    echo '<ul class="min-list link-list">';

    while($programsFromCampus->have_posts()):
        $programsFromCampus->the_post(); 
        $programId = get_the_ID($programsFromCampus);
        $eventsFromPrograms = new WP_Query(array(
            'posts_per_page' => -1, //all events
            'post_type' => 'event',
            'order_by' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                //filter by related program by the $programId received from previous query
                array(
                    'key' => 'related_programs', //ACF field we setup for programs
                    'compare' => 'LIKE',
                    'value' => '"' . $programId  . '"', // $programId is retrieved from the previous loop
                )
            )
        ));

        while($eventsFromPrograms->have_posts()) :
        //echo "Program ID: " . $programId;
        $eventsFromPrograms->the_post();       
        ?>
        <li>
            <a href="<?php the_permalink();?>" ><?php the_title(); ?>       
            </a>  
        </li>
        <?php
        endwhile;
    endwhile;
    wp_reset_postdata();
    echo '</ul>';
endif; 
?>
    
</div>

<?php
} // end main while loop



get_footer();  
?>