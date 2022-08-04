<!-- Review video 37 for breakdown of the query logic -->
<?php
get_header();
pageBanner();
while(have_posts()):
  the_post();?>
<div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo  get_post_type_archive_link('program'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i> All Programs </a>
          <span class="metabox__main"><?php the_title(); ?> </span>
      </p>
  </div>
  <div class="generic-content">
    <?= the_content(); ?>
  </div>
<?php
$relatedProfessors = new WP_Query(array(
  'posts_per_page' => -1,
  'post_type' => 'professor',
  'order_by' => 'title',
  'order' => 'ASC',
  'meta_query' => array( //filter by related programs which have the ID 
    array(
      'key' => 'related_program', //ACF field we setup in events
      'compare' => 'LIKE',
      'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
    )
  )
));
if ($relatedProfessors->have_posts()): ?>
  <hr class="section-break">
  <h2 class="headline headline--medium"><?= get_the_title() ?> Professors</h2>
  <ul>
<?php
  while($relatedProfessors->have_posts()):
    $relatedProfessors->the_post(); ?>
    <li class="professor-card__list-item">
      <a class="professor-card" href="<?php the_permalink();?>">
        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" />
        <span class="professor-card__name"><?php the_title(); ?></span>
      </a>  
    </li>
  <?php
  endwhile; ?>
  </ul>
<?php
  wp_reset_postdata(); // reset the global post object to the url based query
endif;
// get the related event associated with this program which is selected in the acf field within Event posts
$today = date('Ymd');
$relatedEvents = new WP_Query(array(
  'posts_per_page' => 2,
  'post_type' => 'event',
  'meta_key' => 'event_date', //ACF field date value
  'order_by' => 'meta_value_num',
  'order' => 'ASC',
  //only show posts in the future, not the past
  'meta_query' => array(
    array(
      'key' => 'event_date',
      'compare' => '>=',
      'value' => $today,
      'type' => 'numeric'
    ),
    //filter by related programs which have the ID
    array(
      'key' => 'related_program', //ACF field we setup in events
      'compare' => 'LIKE',
      'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
    )
  )
));
if($relatedEvents->have_posts()): ?>
  <hr class="section-break">
  <h2 class="headline headline--medium">Upcoming <?= get_the_title() ?> Events</h2>
  <?php
  while($relatedEvents->have_posts()):
    $relatedEvents->the_post(); 
    get_template_part('template-parts/content', 'event');
  endwhile;
endif;
wp_reset_postdata();
//get the related campuses for this program from ACF
$relatedCampuses = get_field('related_campus');
if($relatedCampuses) :
  echo "<hr class='section-break'>";
  echo "<h2 class='headline headline--medium'>" . get_the_title() . " is available at these campuses:</h2>";
  echo "<ul class='min-list link-list'>";
  foreach($relatedCampuses as $campus) {
    //$campus is used as the ID to get the permalink and the title from
    ?>
    <li><a href="<?php echo get_the_permalink($campus); ?>"> <?php echo get_the_title($campus); ?></a></li>
    <?php
  }
  echo "</ul>";
endif;?>
</div>
<?php
endwhile;
get_footer();?>