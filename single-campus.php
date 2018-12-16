<?php  
get_header();  

    while(have_posts( )) {
        the_post();//keeps track of which post we are working with
        ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
            <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"><?= the_title(); ?></h1>
            <h2 class="headline headline--medium">Keep up with our latest events.</h2>
            <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
         </div>
    </div>

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
            <?php echo $mapLocation['address']; ?>
            </div>
    
            </div>
        </div>
    </div>

<?php }
get_footer();  
?>