<?php 

  get_header(); 

  while(have_posts()) {
    the_post(); ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro">
        <p>TODO: Learn how the school of your dreams got started.</p>
      </div>
    </div>  
  </div>

  <div class="container container--narrow page-section">

    <?php 
      $id = wp_get_post_parent_id(get_the_id());
      if ($id) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($id); ?>">
        <i class="fas fa-angle-double-left" aria-hidden="true"></i> Back to <?php echo get_the_title($id); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
    <?php } ?>
    
    <?php 
    $testArr = get_pages(array(
      'childOf' => get_the_ID()
    ));
    if ($id || $testArr) { ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?></a></h2>
      <ul class="min-list">
        <?php 
          if ($id) {
            $findChildOf = $id;
          } else {
            $findChildOf = get_the_ID();
          }
          wp_list_pages(array(
            'title_li' => null,
            'child_of' => $findChildOf,
            'sort_column' => 'menu_order'
          ));
        ?>
      </ul>
    </div>
    <?php }; ?>

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>
  <?php };

  get_footer();

?>