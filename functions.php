<?php

  function pageBanner($args = null) {
    if (!$args['title']) {
      $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
      if (get_field('page_banner_background_image')) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
    ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" 
        style="background-image: url(<?php echo $args['photo']; ?>);">
      </div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>  
    </div>
  <?php }

  function university_files() {
    wp_enqueue_script('university_main_js', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );
    wp_enqueue_style('Roboto-Condensed', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', '//use.fontawesome.com/releases/v5.3.1/css/all.css');
    wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), null, microtime());
  }

  add_action('wp_enqueue_scripts', 'university_files');

  function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
  }

  add_action('after_setup_theme', 'university_features');

  function university_adjust_queries($query) {
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('post_per_page', -1);
    }
    
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
      $today = date('Ymd');
      $query->set('meta-key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ));
    }
    
  }

  add_action('pre_get_posts', 'university_adjust_queries');
  
?>