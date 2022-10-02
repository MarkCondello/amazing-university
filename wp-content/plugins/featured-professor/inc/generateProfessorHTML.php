<?php
function generateProfessorHtml($ProfessorId) {
  $professorPost = new WP_Query([
    'post_type' => 'professor',
    'p' => $ProfessorId,
  ]);
  // var_dump($professorPost->posts[0]);
  while($professorPost->have_posts()):
    $professorPost->the_post();

    ob_start(); ?>
    <div class="professor-callout">
      <div class="professor-callout__photo"
        style="background-image: url('<?= the_post_thumbnail_url('professorPortrait') ?>')"
      >
      </div>
      <div class="professor-callout__text">
        <h5><?php the_title(); ?></h5>
        <p><?= wp_trim_words(the_content(), 30) ?></p>
        <?php
          $relatedPrograms = get_field('related_program');
          if($relatedPrograms): ?>
          <p><?= the_title() ?> teached:
           <?php foreach($relatedPrograms as $key => $program):
            echo get_the_title($program);
            if($key != array_key_last($relatedPrograms) && count($relatedPrograms)){ echo ', ';}?>
            <?php
           endforeach; ?>
    <?php endif;?>
          <p><strong>
            <a href="<?= the_permalink() ?>">Learn more about <?= the_title() ?></a>
          </strong></p>
      </div>
    </div>
    <?php
  endwhile;
  wp_reset_postdata();
  return ob_get_clean();
}