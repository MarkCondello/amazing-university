<!-- Review video 37 for breakdown of the query logic -->
<?php
get_header();
pageBanner();
$programTitle = '';
while(have_posts()):
  the_post();
  $programTitle = get_the_title();
?>
<div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo  get_post_type_archive_link('program'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i> All Programs </a>
          <span class="metabox__main"><?= $programTitle ?> </span>
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
  echo "</ul>
  <p>Program ID: ". get_the_ID() ."</p>";
endif;
wp_reset_postdata(); ?>
  <hr class="section-break">
  <h2 class="headline headline--medium"><?= the_title() ?> program ratings</h2>
  <ul class='min-list link-list' id="rating-list">
<?php
$ratingQueryParams = [
  'post_type' => 'rating',
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'DESC',
  'meta_query' => [
    [
      'key' => 'program_id',
      'compare' => '=',
      'value' => get_the_ID(),
    ],
  ],
];
$ratingsQuery = new WP_Query($ratingQueryParams);
// Rating starts here
if ($ratingsQuery->have_posts()):
  while($ratingsQuery->have_posts()):
    $ratingsQuery->the_post();
    $author_id = get_post_field('post_author', get_the_ID());
    $user_created_this_rating = $author_id == get_current_user_id();
    // echo 'post author ID: ' . $author_id . '. Current userID: ' . get_current_user_id() . '<br>';
    // echo '$user_created_this_rating = ' . $user_created_this_rating;
    ?>
    <!-- // Add in the rating
    // if the rating is the current users, add delete and edit features -->
    <li class="rating">
      <div class="row">
        <div class="two-thirds">
          <div class="stars">
  <?php   for($i = 0; $i < get_field('rating'); $i++){
            echo "<span class='fa fa-star'></span>";
          } ?>
          </div>
          <div class="content">
            <?= get_the_content() ?>
          </div>
          <?php $authors_name = get_field('is_anonymous') == 'true' ? 'Anonymous' : the_author_meta('user_nicename') ?>
          <cite><small class="author">Written by: <?= $authors_name ?></small></cite>
        </div>
        <div class="one-third">
    <?php if ($user_created_this_rating): ?>
          <span class="edit-rating"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
          <span class="delete-rating" data-rating-id="<?= get_the_ID() ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
    <?php endif; ?>
        </div>
      </div>
<?php if ($user_created_this_rating): ?>
      <div class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="create-rating">
            <h2>Update your rating this program</h2>
            <label for="program_rating">Rating out of 5</label>
            <div>
              <input class="new-rating-number" type="range" min="1" max="5" name="program_rating" value="<?= get_field('rating'); ?>"/>
              <br></p></br>
            </div>
            <textarea class="new-rating-body" placeholder="Your rating goes here..."><?= get_the_content(); ?></textarea>
            <!-- Include option for user to remain Anonymous -->
            <span>Remain Anonymous?</span>
            <div>
              <label>Yes</label>
              <input
                class="new-rating-is-anonymous"
                type="checkbox"
                name="is_anonymous"
        <?php   if (get_field('is_anonymous')): echo 'checked="checked"'; endif ?>
              >
            <br></p></br>
            <span class="update-rating" data-rating-id="<?= the_ID(); ?>">Update rating</span>
          </div>
        </div>
      </div>
<?php endif; ?>
    </li>
    <?php
  endwhile;
  wp_reset_postdata();
else: ?>
  <li class="no-ratings-message">There were no rating for this program. Create one below.</li>
<?php
endif; ?>
  </ul>
<?php
if (is_user_logged_in() && userIsSubscriber()):
  $currentUsersRatingsQuery = new WP_Query(array_merge(['author' => get_current_user_id()], $ratingQueryParams));
  // echo '$currentUsersRatingsQuery->found_posts = ' . $currentUsersRatingsQuery->found_posts;
  // echo '<br>Username: ' . wp_get_current_user()->user_login;
  if ($currentUsersRatingsQuery->found_posts == 0):
?>
  <div class="container container--narrow page-section">
    <div class="create-rating">
      <h2>Add your rating for the <?= $programTitle ?> program</h2>
      <label for="program_rating">Rating out of 5</label>
      <div>
      <input class="new-rating-number" type="range" min="1" max="5" name="program_rating" value="5"/>
      <br></p></br>
      </div>
      <textarea class="new-rating-body" placeholder="Your rating goes here..."></textarea>
      <!-- Include option for user to remain Anonymous -->
      <span>Remain Anonymous?</span>
      <div>
        <label>Yes</label>
        <input
          class="new-rating-is-anonymous"
          type="checkbox"
          name="is_anonymous"
        >
        <br></p></br>
      </div>
      <span class="submit-rating" data-program-id="<?= the_ID()?>">Create rating</span>
      <!-- If user tries to add another rating show an error message -->
      <!-- <span class="rating-limit-message">You have reached your rating limit. Delete a previous rating to create a new one.</span> -->
    </div>
  </div>
<?php
  else:
    // Used for debugging
    // echo '<pre>';
    // var_dump($currentUsersRatingsQuery->posts[0]);
    // echo '</pre>';
  endif;
endif;?>
</div>
<?php
endwhile;
get_footer();?>