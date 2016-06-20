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
require_once plugin_dir_path( __FILE__ ).'/adminpages/enrico_admin_dir_refresh.php';



//Register custom post type "enrico"
add_action( 'init', 'enrico_register_post' ); //function in enrico_taxonomy

//Metabox for entering and displaying company info for enrico posts
add_action('add_meta_boxes', 'enrico_meta_box_add');
add_action('save_post', 'update_enrico_post_meta', 10, 2);

//Create options setting menu page
add_action('admin_menu','enrico_settings_menu');
add_action( 'admin_menu', 'enrico_admin_import_page' );
add_action('admin_menu','enrico_dir_refresh_page');
add_action('admin_init','enrico_settings_register');



// Page for Import
add_action('admin_post_import_search_form_submit','enrico_import_run_search');
add_action('admin_post_import_selected_perform','enrico_import_companies_selected');
add_action('admin_post_directory_refresh_submit','enrico_directory_refresh_run');

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
add_action( 'wp_enqueue_scripts', 'enrico_add_scripts' );

//Using pre get posts to alter number of posts for enrico archive pages/taxonomy pages
add_action( 'pre_get_posts', 'enrico_archive_pagesize', 1 );



function enrico_add_scripts() {
        wp_enqueue_style( 'enrico-style', plugins_url('/css/enrico-style.css', __FILE__) );
        wp_enqueue_script('enrico-scripts', plugins_url( '/js/enrico-scripts.js', __FILE__ ));
        
        if(get_option( 'enrico_map_preferredMap' )=='google'){
                wp_enqueue_script('google-maps', "https://maps.googleapis.com/maps/api/js?key=".get_option("enrico_googleapi_key")."&callback=initMap");
                    }
      
        if(get_option( 'enrico_map_preferredMap' )=='eniro'){
            //javascript source to cover both http and https requests
                wp_enqueue_script('eniro-maps', "http://kartor.eniro.se/rs/eniro.js?partnerId=".get_option("enrico_map_partnerId")."&profile=se");
                wp_enqueue_script('eniro-maps-https', "https://map.eniro.no/rs/eniro.js?partnerId=".get_option("enrico_map_partnerId")."&profile=se&https");
                    }
        
    }
    
function enrico_search_form($template_path) {
        global $wp_query;
        
  
        if( $wp_query->is_search && $wp_query->partner_type ) {
               $template_path = plugin_dir_path(__FILE__).'/templates/MySearch.php';
              }
    return $template_path;
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
    

    //check if single post and  post type enrico
    elseif( is_single() && get_post_type()=='enrico'){
        
            if ($theme_file = locate_template (array('single-enrico.php'))){
                $template_path = $theme_file;
            }
            else {
                $template_path = plugin_dir_path(__FILE__).'/templates/single-enrico.php';
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

function enrico_archive_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_post_type_archive( 'enrico' ) || is_tax( 'partner_type')) {
        // Display X posts for the custom post type called 'enrico' OR 'partner_type' taxonomy
        $query->set( 'posts_per_page', get_option("enrico_archive_posts_per_page") );
        // Display only 
        $query->set( 'post_status','publish');
        return;
    }
    
    
}
?>