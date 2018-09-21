<?php get_header(); 
  pageBanner(array(
    'title' => get_the_archive_title(),
    'subtitle' => get_the_archive_description()
  ));
?>

  <div class="container container--narrow page-section">
    <?php 
    
    while(have_posts()) {
      the_post(); ?>
      <div class="post-item">
        <h2><a class="headline headline--medium headline--post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="metabox">
          <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('m.d.y'); ?> in <?php echo get_the_category_list(', ') ?></p>
        </div>
        <div class="generic-content">
          <?php the_excerpt(); ?>
          <p><a class="btn btn-info" href="<?php the_permalink(); ?>">Continue Reading<i class="fas fa-arrow-right ml-2"></i></a></p>
        </div>
      </div>
      <?php
    }

    ?>
    <div class="text-center"><?php echo paginate_links(); ?></div>  
    
  </div>

<?php get_footer(); ?>