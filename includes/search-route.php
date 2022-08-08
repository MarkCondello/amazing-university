<?php
// details: https://www.udemy.com/course/become-a-wordpress-developer-php-javascript/learn/lecture/7837916
function universityRegisterSearch() {
  register_rest_route('university/v1', 'search', [
    'method' => 'GET', // WP_REST_SERVER::READABLE
    'callback' => 'universitySearchResults',
  ]);
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults (){
  return 'Congrate dude, here is your route..';
}