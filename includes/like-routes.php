<?php
function registerLikeRoutes() {
  register_rest_route('university/v1', 'add-like', [
    'methods' => 'POST',
    'callback' => 'addLike',
    'permission_callback' => '__return_true',
  ]);
  register_rest_route('university/v1', 'delete-like', [
    'methods' => 'DELETE',
    'callback' => 'deleteLike',
    'permission_callback' => '__return_true',
  ]);
}
add_action('rest_api_init', 'registerLikeRoutes');

function addLike($data) {
  if (is_user_logged_in()) {
    $professorId = sanitize_text_field($data['professorId']);
    $likeExistsQuery = new WP_Query([
      'post_type' => 'like',
      'post_status' => 'publish',
      'meta_query' => [
        [
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professorId,
        ],
      ],
    ]);
    if ($likeExistsQuery->found_posts == 0 && get_post_type($professorId) == 'professor') {
      return wp_insert_post([
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => 'Like you know? Yarr..',
        'meta_input' => [
          'liked_professor_id' => $professorId,
        ],
      ]);
    } else {
      die('Invalid professor ID');
    }
  }
  die('Only logged in users can create a like.');
}
function deleteLike($data) {
  if (is_user_logged_in()) {
    $likeId = sanitize_text_field($data['likeId']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
      wp_delete_post($likeId, true);
      return 'Like succesfully deleted';
    }
    //die('You dont have permissions to delete a like.');
    die('You dont have permissions to delete a like. $likeId :' . $likeId. ' Current User:' . get_current_user_id() . ' post_author post field: ' . get_post_field('post_author', $likeId));
  }
  die('Only logged in users can delete a like.');

}