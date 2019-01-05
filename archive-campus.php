<!-- campus posts list template with google map-->
<?php get_header();  
pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'We have several conveniently located campuses.',
  )
);
?>
<div class="acf-map">
  <?php
    while(have_posts()){
        the_post(); 
        $mapLocation = get_field("map_location");
        ?>
        <div class="marker" data-lat ="<?=  $mapLocation['lat']; ?>" data-lng="<?= $mapLocation['lng']; ?>">
          <a href="<?php the_permalink(); ?>"><h3><?= the_title(); ?></h3></a>
          <?php echo $mapLocation['address']; ?>
        </div>
      <?php
    }
  ?>
</div>
<?php get_footer();  ?>
