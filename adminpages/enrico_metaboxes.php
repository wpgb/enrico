<?php
// Create Metabox to input Enrico Company data

function enrico_meta_box_add($post) {
    add_meta_box("enrico-meta-box", 
                "Enrico Company Details",
                "enrico_details_metabox", 
                "enrico", "normal", "high");
 
}

// defininng the input fields in the Meta box (label,input type,extra attributes- leave empty string "" if not needed)
$GLOBALS['enrico_text_fields'] = array(
    'enrico-streetName' => array('Street Name', 'text','size="50"'),//Do not change the value!
   
    'enrico-postBox' => array('Post Box', 'text','size="50"'), //Do not change the value!
    
    'enrico-postCode' => array( 'Post Code', 'text','size="50"'), //Do not change the value!
    
    'enrico-postArea' => array( 'Post Area', 'text','size="50"'), //Do not change the value!
   
    'enrico-phoneNumber' => array( 'Phone', 'tel','size="50"'), //Do not change the value!
    
    'enrico-mobileNumber' => array( 'Mobile', 'tel','size="50"'), //Do not change the value!
    
    'enrico-faxNumber' => array( 'Fax', 'tel','size="50"'), //Do not change the value!
    
    'enrico-orgNumber' => array( 'Org Number', 'text','size="50"'), //Do not change the value!
    
    'enrico-homepage' => array( 'Web Page', 'url','size="50"'), //Do not change the value!
    
    'enrico-facebook' => array( 'Facebook', 'url','size="50"'), //Do not change the value!
    
    'enrico-latitude' => array( 'Latitude', 'number','size="50" step="any"'), //Do not change the value!
   
    'enrico-longitude' => array( 'Longitude', 'number','size="50" step="any"'), //Do not change the value!
    
    'enrico-infoPageLink' => array( 'Infopage','url','size="50"'), //Do not change the value!
   
    'enrico-email' =>array( 'Email', 'email','size="50"'),
    
    ); 


// The nonce

function enrico_details_metabox($post){
   wp_nonce_field(basename(__FILE__), "enrico-meta-box-nonce");

//Initialize the auto update radio button when not set

if(!get_post_meta($post->ID, "enrico-auto-update" , true)){
         update_post_meta( $post->ID, 'enrico-auto-update','on' );}

//Initialize Country Code se (Sweden) if not set yet        
if(!get_post_meta($post->ID, "enrico-countrycode" , true)){
         update_post_meta( $post->ID, 'enrico-countrycode','se' );}

      ?> 
     <div>
          <table class="form-table">
               
                <tr valign="top">
                    <th scope="row">Eniro ID</th>
                        <td><input name="enrico-eniroId" type="text" size="25" placeholder="Please enter Eniro ID"
                                value="<?php echo get_post_meta($post->ID, 'enrico-eniroId', true) ?>">
                        <input type="submit" value="Submit"></td>
                
                <tr valign="top">
                    <th scope="row">Country Search: </th>        
                	    
	    		<td><input type="radio" name="enrico-countrycode" value="se" 
	    		        <?php checked( 'se', get_post_meta($post->ID, "enrico-countrycode" , true) ); ?> /> Sweden (eniro.se)
	  			<input type="radio" name="enrico-countrycode" value="no"
	  			        <?php checked( 'no', get_post_meta($post->ID, "enrico-countrycode" , true) ); ?> /> Norway (gulesider.no)
	  			<input type="radio" name="enrico-countrycode" value="dk"
	  			        <?php checked( 'dk', get_post_meta($post->ID, "enrico-countrycode" , true) ); ?> /> Denmark (krak.dk)</td>
                        
                
                <tr valign="top">
                    <th scope="row">About<br><i>enter your own description</i></th>
                        <td>        
                            <?php 
                                
                                $about = get_post_meta( $post->ID, 'enrico-about', true );

                      // Settings that we'll pass to wp_editor
                      $args = array (
                            'tinymce' => false,
                            'quicktags' => true,
                            'media_buttons' => false,
                            'textarea_rows' => 5,
                            'teeny' => true,
                            
                      );
                      wp_editor( $about, 'enrico-about', $args );
                      
                      ?>
  
  
</td></tr>
        
                <tr valign="top">
                    <th scope="row">Eniro Info</th>
                        
                    <td>   <input name="enrico-auto-update" type="radio" value="on"
                        <?php checked( 'on', get_post_meta($post->ID, "enrico-auto-update" , true) ); ?> /> Update Eniroinfo  |  
                        
                        <input name="enrico-auto-update" type="radio" value="off"
                        <?php checked( 'off', get_post_meta($post->ID, "enrico-auto-update" , true) ); ?> /> Don't update</td>

                <?php //Loop through and display all the text fields below the Eniro ID with current value (if exists)
            
                foreach ($GLOBALS['enrico_text_fields'] as $key => $value){ ?>
                    <tr valign="top">
                       <th scope="row"><?php echo $value[0] ?></th>
                        <td><input name="<?php echo $key ?>" type="<?php echo $value[1] ?>" <?php echo $value[2] ?> 
                                value="<?php echo get_post_meta($post->ID, $key, true) ?>"></td>
                        
                        
                    </tr>
                <?php 
                }
                ?>
                
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
    
    //Save Country code 
    update_post_meta( $post_id, 'enrico-countrycode', $_POST[ 'enrico-countrycode'] ) ;
    
    //Save the Eniro ID if set
    if( isset( $_POST[ 'enrico-eniroId' ] ) ) {
        update_post_meta( $post_id, 'enrico-eniroId', sanitize_text_field( $_POST[ 'enrico-eniroId'] ) );
    }
    
    //Save the text area 'eniro-about' field
    if( isset( $_POST[ 'enrico-about' ] ) ) {
        update_post_meta( $post_id, 'enrico-about', stripslashes( $_POST[ 'enrico-about'] ) );
    }
    
    //Loop through and save all textfields with current value
    foreach ($GLOBALS['enrico_text_fields'] as $key => $value){
        if( isset( $_POST[ $key ]) ) 
            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key] ) );
    }
   if(get_post_meta($post->ID, "enrico-auto-update" , true)=='on'){
                $eniro_search_result = get_eniroinfo_single_ID($post);}
        else{ $eniro_search_result = NULL;}

    //If searchresult is returned:
    if($eniro_search_result){
    
        //Loop through and save all textfields returned from Eniro Search with new(?) value (if exists)
    
         foreach ($eniro_search_result->res_row(0) as $key => $value){
                if($value)
                    update_post_meta($post_id,'enrico-'.$key,$value);
                    
                elseif(isset( $_POST[ 'enrico-'.$key ] ) ) 
                    update_post_meta( $post_id, 'enrico-'.$key, sanitize_text_field( $_POST[ 'enrico-'.$key] ) );
                    }
    
    
    
        //Update post title, slug and excerpt
        $post_args = array(
                        'ID' => $post_id,
                        'post_title' => $eniro_search_result->res_row(0)['companyName'],
                        'post_name' => wp_unique_post_slug( $eniro_search_result->res_row(0)['companyName'], $post_id, 'None', 'enrico', 'None'),
                        'post_excerpt' => $eniro_search_result->res_row(0)['companyText'],);
        

        //Needed to avoid endless loop from wp_update!
        remove_action('save_post', 'update_enrico_post_meta',10);
        
        wp_update_post( $post_args );
        
        //action re-added after wp_update
        add_action('save_post', 'update_enrico_post_meta',10,2); 
    }//Endif
    
    return;
}

?>