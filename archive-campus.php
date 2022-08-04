<!-- campus posts list template with google map-->
<?php get_header();
pageBanner([
  'title' => 'Our Campuses',
  'subtitle' => 'We have several conveniently located campuses.',
  'photo' => get_template_directory_uri() . '/images/ocean.jpg',
]);?>
<div class="acf-map">
<?php
while(have_posts()): the_post();
  $mapLocation = get_field("map_location"); ?>
  <div class="marker" data-lat ="<?= $mapLocation['lat']; ?>" data-lng="<?= $mapLocation['lng']; ?>">
    <a href="<?php the_permalink(); ?>">
      <h3><?= the_title(); ?></h3>
    </a>
    <?= $mapLocation['address']; ?>
  </div>
<?php
endwhile ?>
</div>
<?php get_footer();?>
