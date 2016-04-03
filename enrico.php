<?php
/*
Plugin Name: Enrico
Plugin URI: http://
Description: Eniro API support for registering Company Posts
Version: 1.0
Author: Gabor Bellak
Author URI: https://github.com/wpgb/enrico/
License: GPL
Copyright: Gabor Bellak
*/

require 'enrico_settings.php';
require 'enrico_getdata.php';
require 'enrico_taxonomy.php';
require 'enrico_metaboxes.php';


//Register custom post type "enrico"
add_action( 'init', 'enrico_register_post' ); //function in enrico_taxonomy

//Metabox for entering and displaying company info for enrico posts
add_action('add_meta_boxes', 'enrico_meta_box_add');
add_action('save_post', 'update_enrico_post_meta', 10, 2);

//Create options setting menu page
add_action('admin_menu','enrico_settings_menu');
add_action('admin_init','enrico_settings_register');

//Settings for Custom template for enrico post type
add_filter('template_include', 'enrico_template_include',1);




    

function enrico_template_include($template_path){
   if ( is_post_type_archive('enrico') ){
        //check if post type and single postarchive
        if ($theme_file = locate_template (array('archive-enrico.php'))){
                $template_path = $theme_file;
            }
            else {
                $template_path = plugin_dir_path(__FILE__).'/templates/archive-enrico.php';
            }
    }
   
   
    //check if post type and single post
    elseif( get_post_type()=='enrico'){
        if( is_single() ){
            if ($theme_file = locate_template (array('single-enrico.php'))){
                $template_path = $theme_file;
            }
            else {
                $template_path = plugin_dir_path(__FILE__).'/templates/single-enrico.php';
            }
                
            }
            
        }
    
    return $template_path;
    }


?>