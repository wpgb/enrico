<?php
/*
Plugin Name: Enrico
Plugin URI: http://
Description: Eniro API support for registering Company Posts
Version: 1.0
Author: Gabor Bellak
Author URI: http://
License: GPL
Copyright: Gabor Bellak
*/

require 'enrico_settings.php';
require 'enrico_getdata.php';


//Register custom post type "enrico"
add_action( 'init', 'enrico_register_post' );

//Metabox for entering and displaying company info for enrico posts
add_action('add_meta_boxes', 'enrico_meta_box_add');
add_action('save_post', 'update_enrico_post_meta', 10, 2);

//Create options setting menu page
add_action('admin_menu','enrico_settings_menu');
add_action('admin_init','enrico_settings_register');

//Settings for Custom template for enrico post type
add_filter('template_include', 'enrico_template_include',1);


//Defining Text fields to display in enrico Metabox- below Eniro ID (which is search key
//DO NOT CHANGE name (value) on the records used by the eniro search!

$GLOBALS['enrico_text_fields'] = array(
    'Street Name: '=> 'enrico-streetName', //Do not change the value!
    'Post Box: ' =>'enrico-postBox', //Do not change the value!
    'Post Code: '=> 'enrico-postCode', //Do not change the value!
    'Post Area: '=> 'enrico-postArea', //Do not change the value!
    'Phone: '=> 'enrico-phoneNumber', //Do not change the value!
    'Org Number: '=> 'enrico-orgNumber', //Do not change the value!
    'Web Page: '=> 'enrico-homepage', //Do not change the value!
    'Facebook: '=> 'enrico-facebook', //Do not change the value!
    'Latitud: '=> 'enrico-latitude', //Do not change the value!
    'Longitud: '=> 'enrico-longitude', //Do not change the value!
    'Infopage: '=> 'enrico-infoPageLink', //Do not change the value!
    'Email:'=>'enrico-email',
    
    ); 
    

//Register Custom Post type for enricoanies (post_type= enrico)

function enrico_register_post() {
    $labels = array(
  	'name' => __('Enrico Companies'),
  	'singular_name' => __('company'),
  	'add_new' => 'Add New',
	'add_new_item' => 'Add New Company',
	'edit_item' => 'Edit Company',
	'new_item' => 'New Company',
	'view_item' => 'View Company',
	'search_items' => 'Search Company',
  	);
 
  $args = array(
  	'labels'  => $labels,
  	'description' => 'Enrico',
  	'public' => true,
  	'show_ui' => true,
  	'capability-typ' => 'post',
  	'has_archive' => true,
  	'menu_position' => 5,
  	'supports'      => array( 'title','thumbnail','comments','excerpt', ),
  	'rewrite' => array('slug' => 'enrico'),
  	);

  register_post_type( 'enrico', $args );
  
  
  if (function_exists('add_theme_support')){
      add_theme_support('post-thumbnails');
      set_post_thumbnail_size(220,150);
      add_image_size('logotyp',620,270, true);
       }
}


// Create Metabox to input Enrico Company data

function enrico_meta_box_add($post) {
    add_meta_box("enrico-meta-box", 
                "Enrico Company Details",
                "enrico_details_metabox", 
                "enrico", "normal", "high");
 
}

// The nonce

function enrico_details_metabox($post){
   wp_nonce_field(basename(__FILE__), "enrico-meta-box-nonce");

//Initialize the auto update radio button when not set

if(!get_post_meta($post->ID, "enrico-auto-update" , true)){
         update_post_meta( $post->ID, 'enrico-auto-update','on' );}

      ?> 
     <div>
          <table class="form-table">
               
                <tr valign="top">
                    <th scope="row">Eniro ID: </th>
                        <td><input name="enrico-eniroId" type="text" size="25" placeholder="Please enter Eniro ID"
                                value="<?php echo get_post_meta($post->ID, 'enrico-eniroId', true) ?>">
                        <input type="submit" value="Submit"></td>
                        
                <tr valign="top">
                    <th scope="row">Eniro ID: </th>
                        
                    <td>   <input name="enrico-auto-update" type="radio" value="on"
                        <?php checked( 'on', get_post_meta($post->ID, "enrico-auto-update" , true) ); ?> /> Update Eniroinfo  |  
                        
                        <input name="enrico-auto-update" type="radio" value="off"
                        <?php checked( 'off', get_post_meta($post->ID, "enrico-auto-update" , true) ); ?> /> Don't</td>

                <?php //Loop through and display all the text fields below the Eniro ID with current value (if exists)
            
                foreach ($GLOBALS['enrico_text_fields'] as $key => $value){ ?>
                    <tr valign="top">
                       <th scope="row"><?php echo $key ?></th>
                        <td><input name="<?php echo $value ?>" type="text" size="50" 
                                value="<?php echo get_post_meta($post->ID, $value, true) ?>"></td>
                        
                        
                    </tr>
                <?php 
                }
                ?>
                <tr valign="top">
                    <th scope="row">Save changes: </th>
                    <td><input type="submit" value="Save"></td>
           </table>
           
    </div>
 
 <?php        
}




function update_enrico_post_meta($post_id,$post){
    
        // Verify that the nonce is set and valid.
        if (!isset($_POST["enrico-meta-box-nonce"]) || !wp_verify_nonce($_POST["enrico-meta-box-nonce"], basename(__FILE__)))
        return $post_id;
    
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
                    }
        
     //Save the auto update radio button value
   
    update_post_meta( $post_id, 'enrico-auto-update', $_POST[ 'enrico-auto-update'] ) ;
    
    
    //Save the Eniro ID if set
    if( isset( $_POST[ 'enrico-eniroId' ] ) ) {
        update_post_meta( $post_id, 'enrico-eniroId', sanitize_text_field( $_POST[ 'enrico-eniroId'] ) );
    }
    
    //Loop through and save all textfields with current value
    foreach ($GLOBALS['enrico_text_fields'] as $key => $value){
        if( isset( $_POST[ $value ] ) ) 
            update_post_meta( $post_id, $value, sanitize_text_field( $_POST[ $value] ) );
    }
   if(get_post_meta($post->ID, "enrico-auto-update" , true)=='on'){
                $eniro_search_result = get_eniroinfo_single_post($post);}
        else{ $eniro_search_result = NULL;}

    //If searchresult is returned:
    if($eniro_search_result){
    
        //Loop through and save all textfields returned from Eniro Search with new(?) value (if exists)
    
         foreach ($eniro_search_result as $key => $value){
                if($value)
                    update_post_meta($post_id,'enrico-'.$key,$value);
                    
                elseif(isset( $_POST[ 'enrico-'.$key ] ) ) 
                    update_post_meta( $post_id, 'enrico-'.$key, sanitize_text_field( $_POST[ 'enrico-'.$key] ) );
                    }
    
    
    
        //Update post title, slug and excerpt
        $post_args = array(
                        'ID' => $post_id,
                        'post_title' => $eniro_search_result['companyName'],
                        'post_name' => wp_unique_post_slug( $eniro_search_result['companyName'], $post_id, 'None', 'enrico', 'None'),
                        'post_excerpt' => $eniro_search_result['companyText'],);
        

        //Needed to avoid endless loop from wp_update!
        remove_action('save_post', 'update_enrico_post_meta',10);
        
        wp_update_post( $post_args );
        
        //action re-added after wp_update
        add_action('save_post', 'update_enrico_post_meta',10,2); 
    }//Endif
    
    return;
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


?>