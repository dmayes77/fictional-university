<?php

add_action('rest_api_init', 'universitySearch');

function universitySearch() {
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

function universitySearchResults($data) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'event', 'program', 'campus'),
      's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
  );

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();
    if (get_post_type() == 'post' || get_post_type() == 'page') {
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'premalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'author' => get_the_author()
      ));
    }
    if (get_post_type() == 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
      ));
    }
    if (get_post_type() == 'program') {
      $relatedCapuses = get_field('related_campus');
      
      if ($relatedCapuses) {
        foreach ($relatedCapuses as $campus) {
          array_push($results['campuses'], array(
            'title' => get_the_title($campus),
            'permalink' => get_the_permalink($campus)
          ));
        }
      }
      
      array_push($results['programs'], array(
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }
    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = null;
      if (has_excerpt()) {
        $description = get_the_excerpt();
      } else {
        $description = wp_trim_words(get_the_content(), 6);
      }
      array_push($results['events'], array(
        'title' => get_the_title(),
        'premalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description
      ));
    }
    if (get_post_type() == 'campus') {
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'premalink' => get_the_permalink()
      ));
    }
    
  }

  if ($results['programs']) {
    $programsMetaQuery = array('relation' => '||');

  foreach($results['programs'] as $item) {
    array_push($programsMetaQuery, array(
      'key' => 'programs',
      'compare' => 'LIKE',
      'value' => '"'.$item['id'].'"'
    ));
  }

  $programRelationship = new WP_Query(array(
    'post_type' => array(
      'professor',
      'event'
    ),
    'meta_query' => $programsMetaQuery
  ));

  while($programRelationship->have_posts()) {
    $programRelationship->the_post();

    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = null;
      if (has_excerpt()) {
        $description = get_the_excerpt();
      } else {
        $description = wp_trim_words(get_the_content(), 6);
      }
      array_push($results['events'], array(
        'title' => get_the_title(),
        'premalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description
      ));
    }

    if (get_post_type() == 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
      ));
    }
  }

  $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
  $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
  }

  

  return $results;
}

?>