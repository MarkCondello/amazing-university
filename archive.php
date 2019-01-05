<!-- generic posts list template -->
<?php get_header();  ?>
<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">
        <?php 
        //the below is a new wp function which will output any of the archive types using its default formatting which allows us to not have to use the conditionals
        the_archive_title();
       /*  if(is_category()) {
            single_cat_title();
        }
        if(is_author()) {
            echo "Posts by: "; 
            the_author();
         } */
      ?>
      </h1>
      <p><?= the_archive_description(); ?></p>
       <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>
<div class="container container--narrow page-section">
  <?php while(have_posts()){
    the_post(); ?>
    <div class="post-item">
      <h2 class="headline headline--medium headline--post-title"><a href="<?= the_permalink(); ?>"><?= the_title(); ?></a></h2>
      <div class="metabox">
        <p>Posted by:<?= the_author_posts_link(); ?> on <?= the_time('g M Y'); ?> in <?= get_the_category_list(', '); ?></p>
      </div>
      <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?= the_permalink(); ?>">continue reading</a></p>
      </div>
    </div>
<?php } 
echo paginate_links();
?>
</div>
<?php get_footer();  ?>
