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
          <span class="metabox__main"><?php the_title() ?> </span>
      </p>
  </div>
  <div class="generic-content">
    <?= the_content(); ?>
  </div>
<?php
$relatedProfessors = new WP_Query([
  'posts_per_page' => -1,
  'post_type' => 'professor',
  'order_by' => 'title',
  'order' => 'ASC',
  'meta_query' => [ //filter by related programs which have the ID
    [
      'key' => 'related_program', //ACF field we setup in events
      'compare' => 'LIKE',
      'value' => '"' . get_the_ID()  . '"', // serialize the array values to a string
    ]
  ]
]);
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
if ($relatedEvents->have_posts()): ?>
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
  foreach($relatedCampuses as $campus) { //$campus is used as the ID to get the permalink and the title from ?>
    <li><a href="<?php echo get_the_permalink($campus); ?>"> <?php echo get_the_title($campus); ?></a></li>
    <?php
  }
  echo "</ul>";
endif;
wp_reset_postdata();

$ratingsQuery = new WP_Query([
  // 'author_id' => [get_current_user_id()],
  'post_type' => 'rating',
  'post_status' => 'publish',
  'meta_query' => [
    [
      'key' => 'program_id',
      'compare' => '=',
      'value' => get_the_ID(),
    ],
  ],
]);


// Rating starts here
if ($ratingsQuery->have_posts()): ?>
  <hr class="section-break">
  <h2 class="headline headline--medium"><?= the_title() ?> program ratings</h2>
  <ul class='min-list link-list'>
  <?php
  while($ratingsQuery->have_posts()):
    $ratingsQuery->the_post();
    // Add in the rating
    // if the rating is current users, add delete and edit features
    echo "<li>" . get_the_content() . "</li>";
  endwhile;
  echo "</ul>";
endif;
?>
<!-- Rating feature
Add rating form here that only subscribers can see and create a rating
  Only 1 rating can be created for a program by any one subscriber
  Only the owner of the rating can edit or delete the rating
  Display all the ratings ordered by date -->
<?php
if (is_user_logged_in() && userIsSubscriber()): ?>
  <div class="container container--narrow page-section">
    <div class="create-rating">
      <h2>Add your rating for the <?= the_title() ?> program</h2>
      <label for="program_rating">Rating out of 5</label>
      <div>
      <input class="new-rating-number" type="range" min="1" max="5" name="program_rating" value="5"/>
      <br></p></br>
      </div>
      <textarea class="new-rating-body" placeholder="Your rating goes here..."></textarea>
      <span class="submit-rating" data-program-id="<?= the_ID()?>">Create rating</span>
      <!-- <span class="rating-limit-message">You have reached your rating limit. Delete a previous rating to create a new one.</span> -->
    </div>
  </div>
<?php endif;?>
</div>
<?php
endwhile;
get_footer();?>