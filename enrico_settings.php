<?php
//Options for the eniro api settings (under the Settings Menu)

function enrico_settings_register(){
    register_setting ('enrico_settings_group','enrico_api_profile');
    register_setting ('enrico_settings_group','enrico_api_key');
    register_setting ('enrico_settings_group','enrico_api_version');
    register_setting ('enrico_settings_group','enrico_map_partnerId');
    register_setting ('enrico_settings_group','enrico_map_preferredMap');
}
function enrico_settings_menu(){
add_options_page('Company Settings Page','Enrico','administrator',__FILE__,'enrico_settings_page');

    
}

function enrico_settings_page(){
   ?>
   <div class="wrap">
   <h2>Enrico Company Settings Options</h2>
   
   <form method='post' action='options.php'>
       <?php
       settings_fields( 'enrico_settings_group' );
       do_settings_sections( 'enrico_settings_group' );
       
       if(!get_option( 'enrico_map_preferredMap' )){
           add_option('enrico_map_preferredMap','eniro');
       }
       
       
       ?>
       
       <table class="form-table">
        <tr valign="top">
        <th scope="row">Eniro API Profile: </th>
        <td><input name="enrico_api_profile" size="35" type="text" value="<?php echo get_option("enrico_api_profile");?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Eniro API key: </th>
        <td><input name="enrico_api_key" size="35" type="text" value="<?php echo get_option("enrico_api_key");?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Eniro API version: </th>
        <td><input name="enrico_api_version" type="text" value="<?php echo get_option("enrico_api_version");?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Eniro Maps Partner ID: </th>
        <td><input name="enrico_map_partnerId" type="text" value="<?php echo get_option("enrico_map_partnerId");?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Preferred map service: </th>
        
        <td><input name="enrico_map_preferredMap" type="radio" value="eniro"
                        <?php checked( 'eniro', get_option( 'enrico_map_preferredMap' ) ); ?> /> EniroMap<br>
            
            
            <input name="enrico_map_preferredMap" type="radio" value="google" 
                        <?php checked( 'google', get_option( 'enrico_map_preferredMap' ) ); ?> /> GoogleMap<br>
                        
            <input name="enrico_map_preferredMap" type="radio" value="none" 
                        <?php checked( 'none', get_option( 'enrico_map_preferredMap' ) ); ?> /> None</td>
                        
                      
            

        </tr>
    </table>
    
    <?php submit_button(); ?>
    
    

   </form>
   </div>



   <?php
  
}





?>