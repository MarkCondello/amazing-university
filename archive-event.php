 

<?php get_header();  ?>

<!-- blog listing template for single posts -->
<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">All Events</h1>
      <p>See what is going on in out world.</p>
       <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>

<div class="container container--narrow page-section">
 <?php

    while(have_posts()){
        the_post(); ?>

        <div class="event-summary">
        <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <span class="event-summary__month">Mar</span>
            <span class="event-summary__day">25</span>  
        </a>
        <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
        </div>
        </div>
    <?php
    }
    ?>

</div>
  
<?php get_footer();  ?>


 