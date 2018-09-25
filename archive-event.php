<?php get_header(); 
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
  ));
?>

  <div class="container container--narrow page-section">
    <?php 
    
    while(have_posts()) {
      the_post(); 
      get_template_part('template-parts/content', 'event');
    }

    ?>
    <div class="text-center"><?php echo paginate_links(); ?>
      <hr class="section-break">
      <p>Looking for a recap of past events? <a class="text-info" href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a></p>
    </div>  
    
  </div>

<?php get_footer(); ?>