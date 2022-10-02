<?php
/**
 * Plugin Name: Are you paying attention Block.
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
    // wp_register_style('areyoupayginattentionstyle', plugin_dir_url(__FILE__) . '/build/index.css');
    // wp_register_script('areyoupayginattentionscript', plugin_dir_url(__FILE__) . '/build/index.js', ['wp-blocks', 'wp-element', 'wp-editor'], '1.0', true);

    // register_block_type('mrc-plugin/are-you-paying-attention', [
    register_block_type(__DIR__, [
      'render_callback' => [$this, 'theHTML'], // this is where we delegate saving the block to PHP
      // If we are not using the render_callback, we could enqueue the scripts for the front end using the .json file like this: "viewScript": [ "file:./build/view.js", "example-shared-view-script" ],

    ]);
  }
  public function theHTML($attrs)
  {

    if (!is_admin()):
      // echo '<pre>';
      // var_dump($attrs);
      // echo '</pre>';
      // Load the assets when the block is rendered on the front end
      wp_enqueue_script('attentionfrontend', plugin_dir_url(__FILE__) . '/build/frontend.js', ['wp-element']); // wp-element is WP's version of React
      // wp_enqueue_style('attentionfrontend', plugin_dir_url(__FILE__) . '/build/frontend.css');
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

// adding data as an attribute produces malformed data eg:  data-block-details=<?= wp_json_encode($attrs)