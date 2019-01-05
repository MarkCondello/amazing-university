<!-- single campus post template -->
<?php  
get_header();  
pageBanner();
while(have_posts( )) {
    the_post();
?>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo  get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?> </span></p>
    </div>
    <div class="generic-content">    
            <?= the_content(); ?>
            <div class="acf-map">
        <?php $mapLocation = get_field("map_location");?>
            <div class="marker" data-lat ="<?=  $mapLocation['lat']; ?>" data-lng="<?= $mapLocation['lng']; ?>">
                <h3><?= the_title(); ?></h3>
            <?php echo $mapLocation['address']; 
            ?>
            </div>
        </div>
    </div>
</div>
<?php } // end while loop
get_footer();  
?>