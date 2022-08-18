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
    
    'customQuery' => [], // remove RON
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
        'id' => get_the_ID(),
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
        'id' => get_the_ID(),
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
        'id' => get_the_ID(),
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
  wp_reset_postdata();

  if ($results['programs']):
    $relatedProfessorsMetaQuery = [
      'relation' => 'OR',
    ];
    // find related items from postIds from the results['programs'] array above
    foreach($results['programs'] as $program):
      $relatedProfessorsMetaQuery[] = [
        'key' => 'related_program', //ACF field we setup in events
        'compare' => 'LIKE',
        // 'value' => 60, // Math Program post
        'value' => '"' . $program['id'] . '"', // serialize the array values to a string
      ];
    endforeach;
    // $results['customQuery'] = $relatedProfessorsMetaQuery;
    $relatedProfessors = new WP_Query([
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'order_by' => 'title',
      'order' => 'ASC',
      'meta_query' => $relatedProfessorsMetaQuery,
    ]);
    while($relatedProfessors->have_posts()):
      $relatedProfessors->the_post();
      $item = [
        'post_type' => get_post_type(),
        'author_name' => get_the_author(),
        'title' => get_the_title(),
        'link' => get_the_permalink(),
        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape'),
      ];
      $results['professors'][] = $item;
    endwhile;
    // //remove duplicate items
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
  endif;
  
  return $results;
}