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
      <div class="metabox metabox--position-up metabox--with-home-link">
          <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>">
            <i class="fas fa-angle-double-left mr-2" aria-hidden="true"></i>Events Home</a> 
            <span class="metabox__main">
              <?php 
                $eventDate = new DateTime(get_field('event_date'));
                echo $eventDate->format('F d, Y')
              ?> at <?php 
                $eventTime = new DateTime(get_field('event_time'));
                echo $eventTime->format('g:i a')
              ?>
            </span>
          </p>
      </div>
      <br>
      <hr>
      <div class="generic-content"><?php the_content(); ?></div>
    </div>

  <?php };

  get_footer();

?>