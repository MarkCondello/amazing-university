<!-- single professor post template -->
<?php  
get_header();  
while(have_posts()) {
    the_post(); 
    pageBanner(array(
        'photo' => 'https://petful101.com/wp-content/uploads/2017/11/chihuahua-mating-dogs-sex-humping.jpg'
    )
);
?>
    <div class="container container--narrow page-section">
        <div class="generic-content">  
            <div class="row group">
                <div class="one-third"><?php the_post_thumbnail('professorPortrait');?></div> 
                <div class="two-thirds"><?php  the_content(); ?></div> 
            </div>  
        </div>
            <?php
              $relatedPrograms = get_field('related_programs');       
             if($relatedPrograms):  ?>
                <hr class="section-break"/>
                <h2 class="headline headline--medium>">Subject(s) Taught</h2>
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
<?php } // end while loop
get_footer();  
?>