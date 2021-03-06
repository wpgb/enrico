<?php

//Defining Text fields to display in enrico Metabox- below Eniro ID (which is search key
//DO NOT CHANGE name (value) on the records used by the eniro search!


    

//Register Custom Post type for enrico directory (post_type= enrico)

function enrico_register_post() {
    
    register_taxonomy(
        'partner_type',
        'enrico',
        array(
            'label' => 'Partner Categories',
            'singular_label' => 'Partner',
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'partners'),
        )
    );

    
    $labels = array(
  	'name' => __('Enrico Directory'),
  	'singular_name' => __('company'),
  	'add_new' => 'Add New Company',
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
  	'menu_icon' => 'dashicons-portfolio',
  	'supports'      => array( 'title','thumbnail','comments','excerpt',),
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