<?php  
get_header();  
    while(have_posts( )) {
        the_post();//keeps track of which post we are working with
        ?>

        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?= get_theme_file_uri('/images/ocean.jpg'); ?>"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?= the_title(); ?></h1>
                <div class="page-banner__intro">
                <p>Include the sub field as a custom field content area.</p>
                </div>
            </div>  
            </div>

            <div class="container container--narrow page-section">


<?php  
//if the following return a value which is not 0 (false) it is a child page, requires the current page ID (get_the_ID())
    $the_parent = wp_get_post_parent_id(get_the_ID());
     if($the_parent): 
     ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_permalink($the_parent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($the_parent); ?></a> <span class="metabox__main"><?= the_title(); ?></span></p>
            </div>
<?php endif; ?>  
<!--
            <div class="page-links">
                <h2 class="page-links__title"><a href="#">About Us</a></h2>
                <ul class="min-list">
                <li class="current_page_item"><a href="#">Our History</a></li>
                <li><a href="#">Our Goals</a></li>
                </ul>
            </div>
-->
            <div class="generic-content">
                <?= the_content(); ?>
            </div>

        </div>

        <?php
    }
get_footer();  
?>