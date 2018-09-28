<?php get_header(); 
  pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;'
  ));
?>

  <div class="container container--narrow page-section">
    <?php 
    if (have_posts()) {
      while(have_posts()) {
        the_post(); 
        get_template_part('template-parts/content', get_post_type());
      }?>
      <div class="text-center"><?php echo paginate_links();
    } else {
      echo '<h2 class="headline healine--small-plus">No results match that query</h2>';
    }
    echo '<br><br><br>';
    get_search_form();
     ?></div>  
    
  </div>

<?php get_footer(); ?>