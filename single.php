<?php  
get_header();  
    while(have_posts( )) {
        the_post();//keeps track of which post we are working with
        ?>
        
            <h2><?= the_title(); ?></h2>
 
        <?php the_content(); ?>
        <hr/>
        <?php
    }
get_footer();  
?>