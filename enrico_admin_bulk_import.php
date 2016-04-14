<?php


function enrico_admin_bulk_import_page(){
    	add_submenu_page('edit.php?post_type=enrico', 'EnricoBulkImport', 'Enrico Bulk Import', 
    	'manage_options', 'enrico_bulk_import',
    	'enrico_import_define_bulk_search');
  
}


function enrico_import_define_bulk_search(){
	
	?>
	

	<div class="wrap">
		<h2>Enrico Company Import</h2>
		
	</div>
	

	<form  action="admin-post.php" method="post" >
  
    
	    Search Terms (use comma to delimit):<br>
		    <input type="text" name="search_terms" size="75" placeholder="Example values: advokat, hoteller, vvs">
		    
		    <input type="submit" value="Submit Search"><br>
		   	
		   	<input name='action' type="hidden" value='bulk_import_run'>
   
	    Search Location :<br>
	    	<input type="text" name="geo_area" size="75" placeholder="Example values: Stockholm, københavn, Oslo">
	    <br>
   
	    Country :<br>
	    		<input type="radio" name="country" value="se" checked> Sweden (eniro.se)
	  			<input type="radio" name="country" value="no"> Norway (gulesider.no)
	  			<input type="radio" name="country" value="dk"> Denmark (krak.dk)
		 <br><br>
	  	Max Results:<br>
	    		<input type="radio" name="max_results" value="200" checked> 200
	  			<input type="radio" name="max_results" value="400"> 400
	  			<input type="radio" name="max_results" value="800"> 800
	  			<input type="radio" name="max_results" value="1600"> 1600
	  			<input type="radio" name="max_results" value="99000"> No limit
	  	
	  
  
	</form>
	<?php
	
	}
	
function enrico_bulk_import_run(){
   	
   		        
        //Required Parameters for the query-url
        $country_code= $_POST['country'];

        $api_profile=get_option('enrico_api_profile');
        $api_key=get_option('enrico_api_key');
        $api_version =get_option('enrico_api_version');
    

        $url = 'http://api.eniro.com/cs/search/basic?profile='.get_option('enrico_api_profile')
        											
        												.'&key='.get_option('enrico_api_key')
        												
                        								.'&version='.get_option('enrico_api_version')
        												
        												.'&country='.$_POST['country']
        												
        												.'&search_word='.$_POST['search_terms']
        												
        												.'&geo_area='.$_POST['geo_area']
        												
        												.'&from_list=1'
        												
        												.'&to_list=100'
                        
														;
                        
                        
        
        $eniro_search_result=new EnricoQuery($url);
        
		$eniro_search_result->insert_post_all();

		
		if($eniro_search_result){
		
			if($eniro_search_result->hits()>100){// only do if #hits require additional queries
				
				$number_of_queries = ceil(min($eniro_search_result->hits(),$_POST["max_results"])/100)-1;
				
				for ($x = 1;$x <=$number_of_queries; $x++){

					        $url = 'http://api.eniro.com/cs/search/basic?profile='.get_option('enrico_api_profile')
        											
        												.'&key='.get_option('enrico_api_key')
        												
                        								.'&version='.get_option('enrico_api_version')
        												
        												.'&country='.$_POST['country']
        												
        												.'&search_word='.$_POST['search_terms']
        												
        												.'&geo_area='.$_POST['geo_area']
        												
        												.'&from_list='.(($x*100)+1)
        												
        												.'&to_list='.(($x*100)+100)
                        
														;
				
				
							$eniro_search_result=new EnricoQuery($url);
							
							$eniro_search_result->insert_post_all();
														}
				
			}
   	
		}
		

			
   //	wp_safe_redirect(admin_url('edit.php?post_type=enrico'));exit;
echo 'hits '.$eniro_search_result->hits().'<br>'.$_POST["max_results"].'<br>';
		
	}
?>