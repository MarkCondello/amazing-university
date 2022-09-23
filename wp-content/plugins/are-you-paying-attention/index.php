<?php
/**
 * Plugin Name: Are you paying attention
 * Description: A plugin which generates a Guttenburg block.
 * Version: 1.0.0
 * Author: MRC
 * Author URI: https://github.com/MarkCondello
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Exit if accessed directly.
if (!defined('ABSPATH'))
  exit;

class AreYouPayingAttention{
  public function __construct()
  {
    add_action('init', [$this, 'adminAssets']);
  }
  public function adminAssets()
  {
    wp_register_style('areyoupayginattentionstyle', plugin_dir_url(__FILE__) . '/build/index.css');
    wp_register_script('areyoupayginattentionscript', plugin_dir_url(__FILE__) . '/build/index.js', ['wp-blocks', 'wp-element', 'wp-editor'], '1.0', true);
    register_block_type('mrc-plugin/are-you-paying-attention', [
      'editor_script' => 'areyoupayginattentionscript',
      'editor_style' => 'areyoupayginattentionstyle',
      'render_callback' => [$this, 'theHTML'], // this is where we delegate saving the block to PHP
    ]);
  }
  public function theHTML($attrs)
  {
    if (!is_admin()):
      // Load the assets when the block is rendered on the front end
      wp_enqueue_script('attentionfrontend', plugin_dir_url(__FILE__) . '/build/frontend.js', ['wp-element']); // wp-element is WP's version of React
      wp_enqueue_style('attentionfrontend', plugin_dir_url(__FILE__) . '/build/frontend.css');
    endif;
    ob_start(); // anything that comes after this function is added to the buffer?>
    <div class="paying-attention-block">
      <pre style="display:none;"><?= wp_json_encode($attrs) ?></pre> <!-- Load props data into pre tag -->
    </div>
  <?php
    return ob_get_clean();
  }
}
$payingAttention = new AreYouPayingAttention();
// npm wp-scripts does not included React in the build but instead bundles the scripts we create for react