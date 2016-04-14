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
require 'enrico_admin_company_import.php';
require 'enrico_admin_bulk_import.php';


//Register custom post type "enrico"
add_action( 'init', 'enrico_register_post' ); //function in enrico_taxonomy

//Metabox for entering and displaying company info for enrico posts
add_action('add_meta_boxes', 'enrico_meta_box_add');
add_action('save_post', 'update_enrico_post_meta', 10, 2);

//Create options setting menu page
add_action('admin_menu','enrico_settings_menu');
add_action( 'admin_menu', 'enrico_admin_import_page' );
add_action('admin_init','enrico_settings_register');
add_action( 'admin_menu', 'enrico_admin_bulk_import_page' );


// Page for Import
add_action('admin_post_import_search_form_submit','enrico_import_run_search');
add_action('admin_post_import_selected_perform','enrico_import_companies');
add_action('admin_post_bulk_import_run','enrico_bulk_import_run');



//Settings for Custom template for enrico post type
add_filter('template_include', 'enrico_template_include',1);
add_filter( 'taxonomy_template', 'get_custom_taxonomy_template' );

//Add Stylesheet for the plugin
add_action( 'wp_enqueue_scripts', 'enrico_add_stylesheet' );



function enrico_add_stylesheet() {
        wp_enqueue_style( 'enrico-style', plugins_url('/css/enrico-style.css', __FILE__) );
    }
    

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

function get_custom_taxonomy_template($template_path){
    if(is_tax( 'partner_type', '' )){
        if ($theme_file = locate_template (array('taxonomy-partner_type.php'))){
                $template_path = $theme_file;
        }
        else {
        $template_path = plugin_dir_path(__FILE__).'/templates/taxonomy-partner_type.php';
                }
        }   
   return $template_path;
    }





?>