<?php
function registerRatingRoutes()
{
  register_rest_route('university/v1', 'add-rating', [
    'methods'=> 'POST',
    'callback'=> 'addRating'
  ]);
  // need edit and delete routes too
}
add_action('rest_api_init', 'registerRatingRoutes');

function addRating($data)
{
  if (is_user_logged_in() && userIsSubscriber()):
    $programId = sanitize_text_field($data['programId']);
    $rating = sanitize_text_field($data['rating']);
    $content = sanitize_text_field($data['content']);

    $ratingExistsQuery = new WP_Query([
      'author_id' => [get_current_user_id()],
      'post_type' => 'rating',
      'post_status' => 'publish',
      'meta_query' => [
        [
          'key' => 'program_id',
          'compare' => '=',
          'value' => $programId,
        ],
        //should I add the rating meta check too?
      ],
    ]);
    // check for an existing rating with the programID created by the user
    if ($ratingExistsQuery->found_posts == 0 && get_post_type($programId) == 'program') {
      return wp_insert_post([
        'post_type' => 'rating',
        'post_status' => 'publish',
        'post_title' => 'Rating from subscriber ' . get_current_user_id(),
        'post_content' => $content,
        'meta_input' => [
          'program_id' => $programId,
          'rating' => $rating,
        ],
      ]);
    } else {
      // This object below is just for feedback
      return (object)[
        'programId' => $programId,
        'rating' => $rating,
        'content' => $content,
        'author_id' => get_current_user_id(),
        'message' => 'An existing rating with the programID was created by the subsciber',
      ];
      die('Invalid program ID or existing rating by subscriber exists');
    }
  else:
    die('Only logged in subscribers can create a rating.');
  endif;
}