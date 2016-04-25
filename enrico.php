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

//Autoloader to load classes - needs to follow the naming convention used  e.g Enrico_Query, in file /classes/Enrico/Query
spl_autoload_register( 'enrico_autoloader' );

function enrico_autoloader( $class_name ) {
  if ( false !== strpos( $class_name, 'Enrico' ) ) {
    $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    $class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
    require_once $classes_dir . $class_file;
  }
}


require_once plugin_dir_path( __FILE__ ).'/adminpages/enrico_settings.php';
require_once plugin_dir_path( __FILE__ ).'/includes/enrico_getdata.php';
require_once plugin_dir_path( __FILE__ ).'/adminpages/enrico_taxonomy.php';
require_once plugin_dir_path( __FILE__ ).'/adminpages/enrico_metaboxes.php';
require_once plugin_dir_path( __FILE__ ).'/adminpages/enrico_admin_company_import.php';



//Register custom post type "enrico"
add_action( 'init', 'enrico_register_post' ); //function in enrico_taxonomy

//Metabox for entering and displaying company info for enrico posts
add_action('add_meta_boxes', 'enrico_meta_box_add');
add_action('save_post', 'update_enrico_post_meta', 10, 2);

//Create options setting menu page
add_action('admin_menu','enrico_settings_menu');
add_action( 'admin_menu', 'enrico_admin_import_page' );
add_action('admin_init','enrico_settings_register');



// Page for Import
add_action('admin_post_import_search_form_submit','enrico_import_run_search');
add_action('admin_post_import_selected_perform','enrico_import_companies_selected');

//Session 
add_action('init', 'enricoStartSession', 1);
add_action('wp_logout', 'enricoEndSession');
add_action('wp_login', 'enricoEndSession');


//Admin panel for custom post type enrico
add_filter('manage_enrico_posts_columns' , 'enrico_cpt_columns');
add_action( 'manage_enrico_posts_custom_column' , 'custom_enrico_column', 10, 2 );


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

//Setup PHP Session
function enricoStartSession() {
    if(!session_id()) {
        session_start();
    }
}

//Kill PHP Session
function enricoEndSession() {
    session_destroy ();
}


//Admin Custom Post Table setup

function enrico_cpt_columns($columns){
    
                $columns['phoneNumber'] = "Phone";
                
                $columns['email'] = "Email";
                
                $columns['streetName'] = 'Address';
                
                $columns['partner_type'] = 'Partner Categories';
                
                $columns['countrycode'] = "Country";
                
                $columns['postArea'] = 'City';
                
                return $columns;
                
            }

function custom_enrico_column( $column, $post_id ) {
    switch ( $column ) {

        case 'partner_type' :
            $terms = get_the_term_list( $post_id , 'partner_type' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
                break;
         
         case 'streetName' :
            echo get_post_meta( $post_id , 'enrico-streetName' , true ); 
            break;   

        case 'postArea' :
            echo get_post_meta( $post_id , 'enrico-postArea' , true ); 
            break;
            
        case 'countrycode' :
            echo get_post_meta( $post_id , 'enrico-countrycode' , true ); 
            break;
        
        case 'phoneNumber' :
            echo get_post_meta( $post_id , 'enrico-phoneNumber' , true ); 
            break;
            
        case 'email' :
            echo get_post_meta( $post_id , 'enrico-email' , true ); 
            break;
            
            
    }
}

?>