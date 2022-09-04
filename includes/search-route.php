<?php
// details: https://www.udemy.com/course/become-a-wordpress-developer-php-javascript/learn/lecture/7837916
function universityRegisterSearch() {
  register_rest_route('university/v1', 'search', [
    'method' => 'GET', // WP_REST_SERVER::READABLE
    'callback' => 'universitySearchResults',
  ]);
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults ($request) { // arrays get converted to valid JSON
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
    $item = getDefaultDataItems();
    switch(get_post_type()):
      case 'post':
      case 'page':
        $results['generalInfo'][] = $item;
      break;
      case 'professor':
        $item['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape');
        $results['professors'][] = $item;
      break;
      case 'program':
        $results['programs'][] = $item;
        $relatedCampuses = get_field('related_campus');
        if (count($relatedCampuses)):
          foreach($relatedCampuses as $campus):
            $item = getDefaultDataItems($campus);
            $item['id'] = $campus->ID;
            $results['campuses'][] = $item;
          endforeach;
        endif;
      break;
      case 'campus':
        $results['campuses'][] = $item;
      break;
      case 'event':
        $item = array_merge($item, getEventDetails());
        $results['events'][] = $item;
      break;
    endswitch;
  endwhile;
  wp_reset_postdata();

  if ($results['programs']):
    $relatedProfessorsOrEventsMetaQuery = [
      'relation' => 'OR',
    ];
    // find related items from postIds from the results['programs'] array above
    foreach($results['programs'] as $program):
      $relatedProfessorsOrEventsMetaQuery[] = [
        'key' => 'related_program', //ACF field we setup in events
        'compare' => 'LIKE',
        'value' => '"' . $program['id'] . '"', // serialize the array values to a string
      ];
    endforeach;
    $relatedProfessorsOrEvents = new WP_Query([
      'posts_per_page' => -1,
      'post_type' => ['professor', 'event'],
      'order_by' => 'title',
      'order' => 'ASC',
      'meta_query' => $relatedProfessorsOrEventsMetaQuery,
    ]);
    while($relatedProfessorsOrEvents->have_posts()):
      $relatedProfessorsOrEvents->the_post();
      $item = getDefaultDataItems();
      switch(get_post_type()):
        case 'professor':
          $item['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape');
          $results['professors'][] = $item;
          break;
        case 'event':
          $item = array_merge($item, getEventDetails());
          $results['events'][] = $item;
          break;
        endswitch;
    endwhile;
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR)); //remove duplicate items
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR)); //remove duplicate items
  endif;
  $results['campuses'] = array_values(array_unique($results['campuses'], SORT_REGULAR)); //remove duplicate items
  return $results;
}

function getDefaultDataItems($postObj = null) {
  return [
    'post_type' => get_post_type($postObj),
    'author_name' => get_the_author($postObj),
    'title' => get_the_title($postObj),
    'link' => get_the_permalink($postObj),
    'id' => get_the_ID($postObj),
  ];
}

function getEventDetails() {
  $eventDate = new DateTime(get_field(get_the_ID(), 'event_date'));
  $intro = wp_trim_words(get_the_content(), 18);
  if (has_excerpt()){
    $intro = get_the_excerpt();
  } 
  return [
    'event_month' => $eventDate->format('M'),
    'event_day' => $eventDate->format('d'),
    'intro' => $intro,
  ];
}