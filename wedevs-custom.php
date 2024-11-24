<?php
/**
 * Plugin Name: Academy Taxonomy Plugin
 * Plugin URI: https://wedevs.academy
 * Description: This plugin adds a custom taxonomy to WordPress.
 * Version: 0.1.0
 * Author: Firoz mahmud
 * Author URI: https://firoz.co
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: my-taxonomy-plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Wedevs_Custom_texonomy {
    private static $instance;
    public static function get_instance(){
        if(!self::$instance){
            self::$instance= new self();
        }
        return self::$instance;
    } 

    private function __construct(){
        $this->require_classes();
    }

    public function require_classes(){

        require_once __DIR__. '/includes/custom-texo.php';
        
        new Wedevs_Custom_texonomy_make();
    }
}

Wedevs_Custom_texonomy::get_instance();