<?php 

  get_header(); 

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>
    

  <div class="container container--narrow page-section">

    <?php 
      $id = wp_get_post_parent_id(get_the_id());
      if ($id) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($id); ?>">
        <i class="fas fa-angle-double-left" aria-hidden="true"></i> Back to <?php echo get_the_title($id); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
    <?php } ?>

    <div class="generic-content">
      <?php get_search_form(); ?>
    </div>

  </div>
  <?php };

  get_footer();

?>