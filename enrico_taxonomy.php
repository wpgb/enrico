<?php

//Defining Text fields to display in enrico Metabox- below Eniro ID (which is search key
//DO NOT CHANGE name (value) on the records used by the eniro search!


    

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
  	'supports'      => array( 'title','thumbnail','comments','excerpt', 'content'),
  	'rewrite' => array('slug' => 'enrico'),
  	);

  register_post_type( 'enrico', $args );
  
  
  if (function_exists('add_theme_support')){
      add_theme_support('post-thumbnails');
      set_post_thumbnail_size(220,150);
      add_image_size('logotyp',620,270, true);
       }
}


?>