<?php get_header(); 
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
  ));
?>

  <div class="container container--narrow page-section">
    <?php 
    
    while(have_posts()) {
      the_post(); ?>
        <div id="<?php the_title(); ?>" class="event-summary mb-5">
          <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <span class="event-summary__month"><?php
              $eventDate = new DateTime(get_field('event_date'));
              echo $eventDate->format('M')
            ?></span>
            <span class="event-summary__day"><?php
              $eventDate = new DateTime(get_field('event_date'));
              echo $eventDate->format('d')
            ?></span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 14 ); ?><br><a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
          </div>
        </div>
      <?php
    }

    ?>
    <div class="text-center"><?php echo paginate_links(); ?>
      <hr class="section-break">
      <p>Looking for a recap of past events? <a class="text-info" href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a></p>
    </div>  
    
  </div>

<?php get_footer(); ?>