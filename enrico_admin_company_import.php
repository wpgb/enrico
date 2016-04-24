<?php

function enrico_admin_import_page(){
    	add_submenu_page('edit.php?post_type=enrico', 'EnricoImport', 'Enrico Import', 
    	'manage_options', 'enrico_company_import',
    	'enrico_import_define_search');
  
}



function enrico_import_define_search(){
	
	?>
	

	<div class="wrap">
		<h2>Enrico Company Import</h2>
		
	</div>
	

	<form  action="admin-post.php" method="post" >
  
    
	    Search Terms (use comma to delimit):<br>
		    <input type="text" name="search_terms" size="75" placeholder="Example values: advokat, hoteller, vvs">
		   <br> 

		   	
		   	
   
	    Search Location :<br>
	    	<input type="text" name="geo_area" size="75" placeholder="Example values: Stockholm, københavn, Oslo">
	    <br>
   
	    Country :<br>
	    		<input type="radio" name="country" value="se" checked> Sweden (eniro.se)
	  			<input type="radio" name="country" value="no"> Norway (gulesider.no)
	  			<input type="radio" name="country" value="dk"> Denmark (krak.dk)
	  			
		 <br><br>
		 
	  	
	  	 
	  	Status after import :<br>
	    		<input type="radio" name="status" value="publish" checked> Publish
	  			<input type="radio" name="status" value="draft"> Draft
	  			<input type="radio" name="status" value="private"> Private
	  			<input type="radio" name="status" value="pending"> Pending
	  			
	  	<br><br>
	  
	  	<?php	
	  	$taxonomy = 'partner_type';
	  	$taxonomy_meta = get_taxonomy( $taxonomy );
	  	
	  	
	  	$term_args=array(
							  'hide_empty' => false,
							  'orderby' => 'name',
							  'order' => 'ASC'
							);
		
		$tax_terms = get_terms($taxonomy,$term_args);
		
		
		echo $taxonomy_meta->label.' :<br>';
		foreach ($tax_terms as $tax_term) {
	  		
	  	
	  	
	  	echo '<input type="checkbox" name="partner_cat[]" value="'.
	  	
	  				term_exists($tax_term->name,'partner_type')['term_id'].'">'.$tax_term->name;
	  			
	  			} 
		
	
   ?>	
   
   		<br><br>
   
	   	Max Results (# of entries) <i>Applies to Bulk Import</i>:<br>
		    	<input type="radio" name="max_results" value="200" checked> 200
	  			<input type="radio" name="max_results" value="400"> 400
	  			<input type="radio" name="max_results" value="800"> 800
	  			<input type="radio" name="max_results" value="1600"> 1600
	  			<input type="radio" name="max_results" value="99000"> No limit
	  	 
	  	 
   
   		<br><br>
   		
   		Type of import :<br>
	    		<input type="radio" name="enrico_import_interactive" value="true" checked> Interactive
	  			<input type="radio" name="enrico_import_interactive" value="false" > Bulk
	  	<br><br>
	  	
	  	
	  	<input name='action' type="hidden" value='import_search_form_submit'>
  		    
  		<input type="submit" value="Submit Search"><br>
	
	<br><br><br><br>
	<p><i>*) Interactive import will let you select/deselect entries for the import. Max 100 entries can be handled interactively</i></p>
	<p><i>**) Bulk import will import all entries in the Results</i></p>
	</form>
	
		
	<?php
	


}

function enrico_import_run_search(){
	        
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
                        
                        
        
        $enrico_search_result=new Enrico_Query($url);
        
        if($_POST['enrico_import_interactive']== "true"){
        
        
		        //Store the result in Session global 
		        $_SESSION['enrico_search_result'] = $enrico_search_result;
		        
		        $_SESSION['enrico_search_no_rows'] = min(100, $enrico_search_result->hits(),$_POST['max_results']);
		        
		        //store the POST array in SESSION Global for later use
		        
				$_SESSION['enrico_search_input']	= $_POST
		
			
			?>
				<div class="wrap">
					<meta charset="utf-8"/>
				
				<h2>Enrico Company Import- Search Result</h2>
				
				<p>Total No. of hits: <?php echo $enrico_search_result->hits(); ?></p>
				
				<p>Displayed rows: <?php echo $_SESSION['enrico_search_no_rows']; ?></p>
				
				
				<form  action="admin-post.php" method="post" >
					
		
					<?php 
					for ($i = 0; $i < $_SESSION['enrico_search_no_rows']; $i++){
						?>
						
						<?php 
						
						echo '<input type="checkbox" name="import_'.$i.'" value="on" checked>' 
						
							.$enrico_search_result->res_row($i)['eniroId'].' |  '
							
							.$enrico_search_result->res_row($i)['companyName'].' |  '
							
							.$enrico_search_result->res_row($i)['streetName'].' | '
							
							.($enrico_search_result->res_row($i)['postArea']).'<br>';
						
					
					}
					
		  	?>	
			     <br><input type="submit" value="Import Selected Companies"><br>
			     
			     <input name='action' type="hidden" value='import_selected_perform'>
			  
				</form>
			
				</div>
			
<?php
        }//endif interactive import
		
		else {// if Bulk import

		$enrico_search_result->insert_post_all($_POST['status'],
           			$_POST['partner_cat']);

		
			if($enrico_search_result){
			
				if($enrico_search_result->hits()>100){// only do if #hits require additional queries
					
					$number_of_queries = ceil(min($enrico_search_result->hits(),$_POST["max_results"])/100)-1;
					
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
					
					
								$enrico_search_result=new Enrico_Query($url);
								
								$enrico_search_result->insert_post_all($_POST['status'],$_POST['partner_cat']);
															}
					
											}
	   	
									}
				
				wp_safe_redirect(admin_url('edit.php?post_type=enrico'));exit;
									
		} //endif Bulk Import


}

function enrico_import_companies_selected(){
//	only called from Interactive import
	
	//re-load the $enrico_search_result from Session Global
	
	if(isset($_SESSION['enrico_search_result'])) {
    		$enrico_search_result = $_SESSION['enrico_search_result'];
				} else {
    		return;
						}

	
	for ($i = 0; $i < $_SESSION['enrico_search_no_rows']; $i++){
		
		if($_POST["import_".$i]=='on'){ //Check if Import box is checked
            
          // $enrico_search_result->insert_post_row($enrico_search_result->res_row($i)););
           $enrico_search_result->insert_post_row($enrico_search_result->res_row($i), 
           			$_SESSION['enrico_search_input']['status'],
           			$_SESSION['enrico_search_input']['partner_cat']);
 
       			}
				
		}
		
	
	wp_safe_redirect(admin_url('edit.php?post_type=enrico'));exit;
				
}



?>