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

function addLike () {
  return 'Thanks for trying to create a like';
}
function deleteLike () {
  return 'Thanks for trying to delete a like';
}