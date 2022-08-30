<?php
function registerLikeRoutes() {
  register_rest_route('university/v1', 'add-like', [
    'methods' => 'POST',
    'callback' => 'addLike',
  ]);
  register_rest_route('university/v1', 'delete-like', [
    'methods' => 'DELETE',
    'callback' => 'deleteLike',
  ]);
}
add_action('rest_api_init', 'registerLikeRoutes');

function addLike($data) {
  $like = wp_insert_post([
    'post_type' => 'like',
    'post_status' => 'publish',
    'post_title' => 'Create like neo123',
    'meta_input' => [
      'liked_professor_id' => sanitize_text_field($data['professorId']),
    ],
  ]);
  if ($like) {
    return 'Successfully created a like.';
  }
}
function deleteLike() {
  return 'Thanks for trying to delete a like';
}