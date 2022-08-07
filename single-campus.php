<?php
get_header();
pageBanner();
$campusName = '';
while(have_posts()):
    the_post();
    $campusName = get_the_title(); ?>
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
            <?php echo $mapLocation['address']; ?>
            </div>
        </div>
    </div>
<?php
    $relatedPrograms = new WP_Query(array(
        'posts_per_page' => -1, //all programs
        'post_type' => 'program',
        'order_by' => 'title',
        'order' => 'ASC',
        'meta_query' => array( //filter by related campus which have the campus ID
            array(
                'key' => 'related_campus', //ACF field we setup for programs
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"', // serialize the array values to a string
            )
        )
    ));
    if ($relatedPrograms->have_posts()) :
    echo '<hr class="section-break">';
    echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';
    echo '<ul class="min-list link-list">';
    //gather the program Id so it can be used the subsequent related events query
    $programId = "";
    while($relatedPrograms->have_posts()):
        $relatedPrograms->the_post(); 
        $programId = get_the_ID($relatedPrograms);
        // echo "Program ID: " . $programId; ?>
        <li>
        <a href="<?php the_permalink();?>" ><?php the_title(); ?></a>
        </li>
    <?php
    endwhile;
    endif;
    echo '</ul>';

    //Get the related programs related events, already calling $relatedPrograms above
    while($relatedPrograms->have_posts()):
        $relatedPrograms->the_post();
        $programId = get_the_ID($relatedPrograms);
        // echo "Program ID: " . $programId;
        $today = date('Ymd');
        $relatedEvents = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'event',
            'meta_key' => 'event_date', //ACF field date value
            'order_by' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array( //only show eventd in the future, not the past
                array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
                ),
                //filter results by related program ID
                array(
                'key' => 'related_program', //ACF field we setup in events
                'compare' => 'LIKE',
                'value' => '"' .$programId . '"', // serialize the array values to a string
                )
            )
        ));
        if($relatedEvents->have_posts()): ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming Events at <?= $campusName ?></h2>
            <?php
            while($relatedEvents->have_posts()):
            $relatedEvents->the_post(); 
            get_template_part('template-parts/content', 'event');
            endwhile;
        endif;
        wp_reset_postdata();
    endwhile;
  wp_reset_postdata(); ?>
</div>
<?php
endwhile; // end main while loop
get_footer(); ?>