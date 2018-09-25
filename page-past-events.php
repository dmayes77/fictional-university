<?php get_header(); 
pageBanner(array(
  'title' => 'Past Events',
  'subtile' => 'Recap our past events'
));
?>

  <div class="container container--narrow page-section">
    <?php 
    
    $today = date('Ymd');
    $pastEvents = new WP_QUERY(array(
      'paged' => get_query_var('paged', 1),
      'post_type' => 'event',
      'meta_key' => 'event_date',
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '<',
          'value' => $today,
          'type' => 'numeric'
        )
      )
    ));

    while($pastEvents->have_posts()) {
      $pastEvents->the_post();
      get_template_part('template-parts/content', 'event');
    }

    ?>
    <div class="text-center"><?php echo paginate_links(array(
      'total' => $pastEvents->max_num_pages
    )); ?></div>  
    
  </div>

<?php get_footer(); ?>