<?php
// details: https://www.udemy.com/course/become-a-wordpress-developer-php-javascript/learn/lecture/7837916
function universityRegisterSearch() {
  register_rest_route('university/v1', 'search', [
    'method' => 'GET', // WP_REST_SERVER::READABLE
    'callback' => 'universitySearchResults',
  ]);
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults ($request) {
  // arrays get converted to valid JSON
  $query = new WP_Query([
    'post_type' => ['post', 'page', 'professor', 'program', 'campus', 'event'],
    's' => filter_var($request['term'], FILTER_SANITIZE_URL), // sanitize the param 'term'
  ]);
  $results = [
    'generalInfo' => [],
    'professors' => [],
    'programs' => [],
    'events' => [],
    'campuses' => [],
   ];
  while($query->have_posts()):
    $query->the_post();
    // use a switch instead
    if (get_post_type() == 'post' || get_post_type() == 'page') {
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ];
      $results['generalInfo'][] = $item;
    }
    if (get_post_type() == 'professor') {
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape'),
      ];
      $results['professors'][] = $item;
    }
    if (get_post_type() == 'program') {
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ];
      $results['programs'][] = $item;
    }
    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field(get_the_ID(), 'event_date'));
      $intro;
      if (has_excerpt()){
        $intro = get_the_excerpt();
      } else {
        $intro = wp_trim_words(get_the_content(), 18);
      }
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
        'event_month' => $eventDate->format('M'),
        'event_day' => $eventDate->format('d'),
        'intro' => $intro,
      ];
      $results['events'][] = $item;
    }
    if (get_post_type() == 'campus') {
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ];
      $results['campuses'][] = $item;
    }
  endwhile;
  return $results;
  
}