<!-- single event post template -->
<?php  
get_header();  
while(have_posts( )) {
    the_post(); 
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/library-hero.jpg'); ?>"></div>
            <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"><?= the_title(); ?></h1>
            <h2 class="headline headline--medium">Keep up with our latest events.</h2>
            <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
            <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo  get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home </a> <span class="metabox__main"><?php the_title(); ?> </span></p>
        </div>
        <div class="generic-content">    
             <?= the_content(); 
              $relatedPrograms = get_field('related_programs'); 
           
             if($relatedPrograms):  ?>
                <hr class="section-break"/>
                <h2 class="headline headline--medium>">Related Program(s)</h2>
                <ul class="link-list min-list">
                <?php foreach($relatedPrograms as $program) { 
                   // print_r($program); WP Object can be used with the get_SOMENAME methods
                    ?>
                    <li><a href="<?php echo get_the_permalink($program);?>">  <?php echo get_the_title($program);?> </a></li>
                <?php    
                }
                ?>
                </ul>
        <?php endif; ?>
        </div>
    </div>
<?php } // end while loop
get_footer();  
?>