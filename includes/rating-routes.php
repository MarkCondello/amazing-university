<?php
function registerRatingRoutes()
{
  register_rest_route('university/v1', 'add-rating', [
    'methods' => 'POST',
    'callback' => 'addRating',
    'permission_callback' => '__return_true',
  ]);
  register_rest_route('university/v1', 'delete-rating', [
    'methods' => 'DELETE',
    'callback' => 'deleteRating',
    'permission_callback' => '__return_true',
  ]);
  register_rest_route('university/v1', 'update-rating', [
    'methods' => 'POST',
    'callback' => 'updateRating',
    'permission_callback' => '__return_true',
  ]);
}
add_action('rest_api_init', 'registerRatingRoutes');

function updateRating($data)
{
  if (is_user_logged_in() && userIsSubscriber()):
    $ratingId = sanitize_text_field($data['ratingId']);
    $ratingExistsQuery = new WP_Query([
      'p' => $ratingId,
      'author' => get_current_user_id(),
      'post_type' => 'rating',
      'post_status' => 'publish',
    ]);
    if ($ratingExistsQuery->found_posts && get_post_type($ratingId) == 'rating') :
      $rating = sanitize_text_field($data['rating']);
      $content = sanitize_text_field($data['content']);
      $is_anonymous = sanitize_text_field($data['is_anonymous']); // does this val need to be set to on??

      $updatedRatingId = wp_update_post([
        'ID' => $ratingId,
        'post_title' => 'Rating ' . $ratingId . ' updated.',
        'post_content' => $content,
        'meta_input' => [
          'rating' => $rating,
          'is_anonymous' => $is_anonymous,
        ],
      ]);
      $updatedRatingQuery = new WP_Query([
        'p' => $updatedRatingId,
        'author' => get_current_user_id(),
        'post_type' => 'rating',
      ]);
      //send back the newly updated Rating
      $anonymous_field_setting = get_field('is_anonymous', $updatedRatingId);
      $updatedRatingCustomFields = [
        'rating' => get_field('rating', $updatedRatingId),
        'programId' => get_field('program_id', $updatedRatingId),
        'isAnonymous' => $anonymous_field_setting,
        'author-nice-name' => get_the_author_meta('display_name', get_current_user_id()),
        'authorsName' => $anonymous_field_setting == 'true' ? 'Anonymous' : get_the_author_meta('display_name', get_current_user_id())
      ];
      return array_merge((array) $updatedRatingQuery->posts[0], (array) $updatedRatingCustomFields);
    else:
      die('The ratingID to update does not exist or the ID is not for a rating.');
    endif;
  else:
    die('Only logged in subscribers can delete a rating.');
  endif;
}

function deleteRating($data)
{
  if (is_user_logged_in() && userIsSubscriber()):
    $ratingId = sanitize_text_field($data['ratingId']);
    $ratingExistsQuery = new WP_Query([
      'p' => $ratingId,
      'author' => get_current_user_id(),
      'post_type' => 'rating',
      'post_status' => 'publish',
    ]);
    if ($ratingExistsQuery->found_posts && get_post_type($ratingId) == 'rating') :
      wp_delete_post($ratingId, true);
      return 'Rating succesfully deleted';
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
    $is_anonymous = sanitize_text_field($data['is_anonymous']); // does this val need to be set to on??

    $ratingExistsQuery = new WP_Query([
      'author' => get_current_user_id(),
      'post_type' => 'rating',
      'post_status' => 'publish',
      'meta_query' => [
        [
          'key' => 'program_id',
          'compare' => '=',
          'value' => $programId,
        ],
        //should I add the rating meta check too?
        // Yes
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
          'is_anonymous' => $is_anonymous,
        ],
      ]);
      $newRatingQuery = new WP_Query([
        'p' => $newRatingId,
        'author' => get_current_user_id(),
        'post_type' => 'rating',
      ]);
      //send back the newly created Rating
      $anonymous_field_setting = get_field('is_anonymous', $newRatingId);
      $newRatingCustomFields = [
        'rating' => get_field('rating', $newRatingId),
        'programId' => get_field('program_id', $newRatingId),
        'isAnonymous' => $anonymous_field_setting,
        'authorsName' => $anonymous_field_setting == 'true' ? 'Anonymous' : the_author_meta('user_nicename', get_current_user_id())
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
          'ratingMessage' => 'ID param is not a rating.',
        ];
      }
      die('Invalid program ID or existing rating by subscriber exists');
    }
  else:
    die('Only logged in subscribers can create a rating.');
  endif;
}