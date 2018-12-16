<?php get_header();  ?>

<!-- blog listing template for single posts -->
<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">All Programs</h1>
      <p>There is something for everyone. Have a look around.</p>
       <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
    <?php
        while(have_posts()){
            the_post();     
    ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            
     <?php }
        echo paginate_links();
    ?>
    </ul>
</div>
  
<?php get_footer();  ?>


 