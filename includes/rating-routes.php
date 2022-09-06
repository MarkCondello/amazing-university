<?php
function registerRatingRoutes()
{
  register_rest_route('university/v1', 'add-rating', [
    'methods' => 'POST',
    'callback' => 'addRating',
  ]);
  register_rest_route('university/v1', 'delete-rating', [
    'methods' => 'DELETE',
    'callback' => 'deleteRating',
  ]);
  // need edit route too
}
add_action('rest_api_init', 'registerRatingRoutes');

function deleteRating($data)
{
  if (is_user_logged_in() && userIsSubscriber()):
    $ratingId = sanitize_text_field($data['ratingId']);
    $ratingExistsQuery = new WP_Query([
      'p' => $ratingId,
      'author_id' => [get_current_user_id()],
      'post_type' => 'rating',
      'post_status' => 'publish',
    ]);
    if ($ratingExistsQuery->found_posts && get_post_type($ratingId) == 'rating') :
      wp_delete_post($ratingId, true);
      return 'Like succesfully deleted';
    else:
        die('The ratingID to delete does not exist or the ID is not for a rating.');
    endif;
  else:
    die('Only logged in subscribers can delete a rating.');
  endif;
}

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
          'compare' => 'LIKE',
          'value' => '"' . $programId . '"',
        ],
        //should I add the rating meta check too?
      ],
    ]);
    // check for an existing rating with the programID created by the user
    if ($ratingExistsQuery->found_posts == 0 && get_post_type($programId) == 'program') {
      $newRatingId = wp_insert_post([
        'post_author' => get_current_user_id(),
        'post_type' => 'rating',
        'post_status' => 'publish',
        'post_title' => 'Rating from subscriber ' . get_current_user_id(),
        'post_content' => $content,
        'meta_input' => [
          'program_id' => $programId,
          'rating' => $rating,
        ],
      ]);
      $newRatingQuery = new WP_Query([
        'p' => $newRatingId,
        'author_id' => [get_current_user_id()],
        'post_type' => 'rating',
      ]);
      //send back the newly created Rating
      $newRatingCustomFields = [
        'rating' => get_field('rating', $newRatingId),
        'programId' => get_field('program_id', $newRatingId),
      ];
      return array_merge((array) $newRatingQuery->posts[0], (array) $newRatingCustomFields);

    } else {
      // This objects below are just for feedback
      if ($ratingExistsQuery->found_posts) {
        return (object)[
          'ratingFound' => $ratingExistsQuery->posts[0],
        ];
      } else {
        return (object)[
          'ratingMessage' => 'Id param is not a rating.',
        ];
      }
      die('Invalid program ID or existing rating by subscriber exists');
    }
  else:
    die('Only logged in subscribers can create a rating.');
  endif;
}