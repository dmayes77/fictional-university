<?php get_header(); 
  pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'There is something for everyone. Have a look around!'
  ));
?>

  <div class="container container--narrow page-section">
    
    <!-- Programs List -->
    <ul class="link-list min-list">
      <?php while(have_posts()) { the_post(); ?>
        <li><a class="text-info" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php } ?>
    </ul>
    
  </div>

<?php get_footer(); ?>