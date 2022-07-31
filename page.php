<!-- fallback page template for non specific pages -->
<?php
get_header();
while(have_posts()): the_post();
    pageBanner(); ?>
<div class="container container--narrow page-section">
<?php //if the following returns a value which is not 0 (false) it is a child page, requires the current page ID (get_the_ID())
    $the_parent = wp_get_post_parent_id(get_the_ID());
    if ($the_parent): ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?= get_permalink($the_parent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($the_parent); ?></a> <span class="metabox__main"><?= the_title(); ?></span></p>
    </div>
<?php
    endif;
    $parentPages = get_pages(['child_of' => get_the_ID(), ]);
    if ($the_parent || $parentPages ): ?>
    <div class="page-links">
        <h2 class="page-links__title"><a href="<?= get_permalink($the_parent); ?>"><?= get_the_title($the_parent); ?></a></h2>
        <ul class="min-list">
<?php   if ($the_parent) {
            $findChildrenOf = $the_parent;
        } else {
            $findChildrenOf = get_the_ID();
        }
        wp_list_pages([
            'title_li' => NULL,
            'child_of' => $findChildrenOf,
            'sort_column' => 'menu_order'
        ]); ?>
        </ul>
    </div>
<?php
    endif; ?>
    <div class="generic-content">
        <?= the_content(); ?>
    </div>
</div>
<?php
endwhile;
get_footer(); ?>