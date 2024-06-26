<?php
get_header();
while(have_posts()):
    the_post();
    pageBanner();?>
<div class="container container--narrow page-section">
    <div class="generic-content">
        <div class="row group">
            <div class="one-third"><?php the_post_thumbnail('professorPortrait');?></div> 
            <div class="two-thirds like-box-container">
    <?php   $likeCount = new WP_Query([
                'post_type' => 'like',
                'meta_query' => [
                    [
                        'key' => 'liked_professor_id',
                        'compare' => '=',
                        'value' => get_the_ID(),
                    ],
                ]
            ]);
            $likeId = null;
            if (is_user_logged_in()) :
                $likeExists = 'no';
                $likeExistsQuery = new WP_Query([
                    'author' => get_current_user_id(),
                    'post_type' => 'like',
                    'meta_query' => [
                        [
                            'key' => 'liked_professor_id',
                            'compare' => '=',
                            'value' => get_the_ID(),
                        ],
                    ],
                ]);
                $likeExists = $likeExistsQuery->found_posts ? 'yes' : 'no';
                if ($likeExists == 'yes') {
                  $likeId = $likeExistsQuery->posts[0]->ID;
                }
            endif ?>
                <span
                    class="like-box"
                    data-exists="<?= $likeExists ?>"
                    data-professor-id="<?= the_ID() ?>"
                    data-like-id="<?= $likeId ?>"
                >
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="like-count"><?= $likeCount->found_posts ?></span>
                </span>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
<?php   $relatedPrograms = get_field('related_programs');
        if($relatedPrograms): ?>
        <hr class="section-break"/>
        <h2 class="headline headline--medium>">Subject(s) Taught</h2>
        <ul class="link-list min-list">
<?php       foreach($relatedPrograms as $program): ?>
            <li>
                <a href="<?php echo get_the_permalink($program);?>"> <?php echo get_the_title($program);?></a>
            </li>
<?php       endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php endwhile;
get_footer();?>