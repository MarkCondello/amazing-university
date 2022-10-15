<?php

/*
  Plugin Name: Featured Professor Block.
 * Description: A plugin which generates a Guttenburg block.
 * Version: 1.0.0
 * Author: MRC
 * Author URI: https://github.com/MarkCondello
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'inc/generateProfessorHTML.php';

class FeaturedProfessor {
  function __construct()
  {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'professorHTML']);
    add_filter('the_content', [$this, 'addRelatedPosts']);
  }

  
  function onInit()
  {
    register_meta('post', 'featuredprofessor', [
      'show_in_rest' => true,
      'type' => 'number',
      'single' => false, // do not save all the professor Id's in one row, save them to their own row instead
    ]);
    
    wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
    wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');
    
    register_block_type('ourplugin/featured-professor', array(
      'render_callback' => [$this, 'renderCallback'],
      'editor_script' => 'featuredProfessorScript',
      'editor_style' => 'featuredProfessorStyle'
    ));
  }
  
  function professorHTML()
  {
    register_rest_route('featuredProfessor/v1', 'getHTML', [
      'method' => 'GET', // WP_REST_SERVER::READABLE
      'callback' => [$this, 'renderCallback'],
    ]);
  }
  function renderCallback($attributes)
  {
    if ($attributes['professorId']){
      wp_enqueue_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');
      return generateProfessorHtml($attributes['professorId']);
    } else {
      return null;
    }
  }
  function addRelatedPosts($content)
  {
    if (is_singular('professor') && in_the_loop() && is_main_query()) {
      return $content . generateRelatedPost(get_the_ID());
    }
    return $content;
  }
}

$featuredProfessor = new FeaturedProfessor();