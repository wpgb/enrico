<?php

function enrico_dir_refresh_page(){
	
    	add_submenu_page('edit.php?post_type=enrico', 'EnricoDirRefresh', 'Directory Refresh', 
    	'manage_options', 'enrico_dir_refresh',
    	'enrico_refresh_directory_all');
  
}



function enrico_refresh_directory_all(){
	
	?>
	

	<div class="wrap">
		<h2>Enrico Directory Refresh</h2>
		
	</div>
	

	<form  action="admin-post.php" method="post" >
  
    
	    
	  	
	  	<input name='action' type="hidden" value='directory_refresh_submit'>
  		    
  		<input type="submit" value="Refresh all records"><br>

	</form>
	
		
	<?php
	


}

function enrico_directory_refresh_run(){
	?>
	<div class="wrap">
					<meta charset="utf-8"/>
				
				<h2>Enrico Company Import- Search Result</h2>
				
	
	<?php
	
	global $post;
	
	
	$args = array(
							'post_type' => 'enrico',
							'post_status' => 'publish',
							'posts_per_page' => -1
							);
							
	// Start the Custom Loop.
			
			$loop = new WP_Query( $args );
			
			if($loop->have_posts()){
				$api_profile=get_option('enrico_api_profile');
        		$api_key=get_option('enrico_api_key');
        		$api_version =get_option('enrico_api_version');
		
			
			while ( $loop->have_posts() ) : $loop->the_post();
			
				if(get_post_meta($post->ID, "enrico-auto-update" , true)=='on'){
                
                		$eniro_search_result = get_eniroinfo_single_ID($post);}
        	
        		else{	$eniro_search_result = NULL;}
        		
        		
        //If searchresult is returned:
			 if($eniro_search_result){
    
        //Loop through and save all textfields returned from Eniro Search with new(?) value (if exists)
    
        			foreach ($eniro_search_result->res_row(0) as $key => $value){
            			if($value){
                    			update_post_meta($post_id,'enrico-'.$key,$value);
            					}
                		elseif(isset( $_POST[ 'enrico-'.$key ] ) ) {
                    			update_post_meta( $post_id, 'enrico-'.$key, sanitize_text_field( $_POST[ 'enrico-'.$key] ) );
                    			}
        			}
    
			    
			        //Update post title, slug and excerpt
			        $post_args = array(
			                        
			                        'post_title' => $eniro_search_result->res_row(0)['companyName'],
			                        'post_name' => wp_unique_post_slug( $eniro_search_result->res_row(0)['companyName'], $post_id, 'None', 'enrico', 'None'),
			                        'post_excerpt' => $eniro_search_result->res_row(0)['companyText'],);
			        

			        //Needed to avoid endless loop from wp_update!
			        remove_action('save_post', 'update_enrico_post_meta',10);
			        
			        wp_update_post( $post_args );
			        
			        //action re-added after wp_update
			        add_action('save_post', 'update_enrico_post_meta',10,2); 
			        
			        the_title(' <br> Refreshed: ');
			        
			    }//Endif

			endwhile; wp_reset_postdata();
		
			
		echo '<br><br><a href="'.admin_url('index.php').'">Return to Dashboard</a>';
		
//		wp_safe_redirect(admin_url('edit.php?post_type=enrico&page=enrico_dir_refresh'),301);exit;	
		
}

}

?>