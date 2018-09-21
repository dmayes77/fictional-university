<?php

  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>

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
      <?php 
        $relatedPrograms = get_post_field('programs');
        if ($relatedPrograms) {
          echo '<hr class="section-break" >';
          echo '<h2 class="headline headline--medium" >Related Program(s)</h2>';
          echo '<ul class="link-list min-list">';
          foreach($relatedPrograms as $program) { ?>
            <li><a href="<?php echo get_the_permalink($program); ?>">
              <?php echo get_the_title($program)?>
            </a></li>
          <?php } echo '</ul>'; 
        } ?>
        
    </div>

  <?php }

  get_footer();

?>