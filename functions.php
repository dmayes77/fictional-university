<?php

  require get_theme_file_path('/includes/search-route.php');

  function university_rest() {
    register_rest_field('post', 'author', array(
      'get_callback' => function() {return get_the_author();}
    ));
  }

  add_action('rest_api_init', 'university_rest');

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
    wp_enqueue_script('GoogleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyANpNbh_Z8Hq4ssdx1rUVvQ1ihpDC5qlIs', null, microtime(), true );
    wp_enqueue_script('university-main-js', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );
    wp_enqueue_style('Roboto-Condensed', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//use.fontawesome.com/releases/v5.3.1/css/all.css');
    wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), null, microtime());
    wp_localize_script('university-main-js', 'universityData', array(
      'root_url' => get_site_url()
    ));
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
    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
      $query->set('post_per_page', -1);
    }

    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
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

  function universityMapKey($api) {
    $api['key'] = 'AIzaSyANpNbh_Z8Hq4ssdx1rUVvQ1ihpDC5qlIs';
    return $api;
  }

  add_filter('acf/fields/google_map/api', 'universityMapKey');
  

  //Redirect subscriber
  function redirectSub() {
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }

  add_action('admin_init', 'redirectSub');

  function subAdminBar() {
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
      show_admin_bar(false);
    }
  }

  add_action('wp_loaded', 'subAdminBar');

  //Login 
  function headerUrl() {
    return esc_url(site_url('/'));
  }

  add_filter('login_headerurl', 'headerUrl');

  function loginCSS() {
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), null, microtime());
    wp_enqueue_style('Roboto-Condensed', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  }

  add_action('login_enqueue_scripts', 'loginCSS');

  function loginTitle() {
    return get_bloginfo ('name');
  }

  add_filter('login_headertitle', 'loginTitle')
?>