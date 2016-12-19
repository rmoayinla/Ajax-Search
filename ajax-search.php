<?php
/*
* Plugin Name: Abbey Ajax Search
* Description: Search your wordpress site using Ajax 
* Author: Rabiu Mustapha
* Version: 0.1
* Text Domain: abbey-ajax-search

*/

class Abbey_Ajax_Search{

	public function __construct(){
		add_action ( "wp_enqueue_scripts", array ( $this, "enque_js" ) );

		add_action ( "wp_ajax_nopriv_ajax_search", array ( $this, "load_search_results" ) );

		add_action ( "wp_ajax_ajax_search", array ( $this, "load_search_results" ) );
	}

	function enque_js(){
		if( is_search() ){
			wp_enqueue_script( "ajax-search-script", plugin_dir_url( __FILE__ )."/ajax-search.js", 
			array( "jquery" ), 1.0, true );

			wp_localize_script( "ajax-search-script", "AjaxSearch", 
				array(
					"ajax_url"		 => 	admin_url( "admin-ajax.php" ), 
					"spinner_url" 	=> 		admin_url( "images/spinner.gif" )
				) 
			);

		}
	}

	function load_search_results(){
		global $wp_query;
		if( empty( $_POST["action"] ) || $_POST["action"] !== "ajax_search" )
			return; 

		$search_query_args = array( 'post_status' => 'publish', 's' => $_POST["s"] );

		$search_query = new WP_Query( apply_filters( "ajax_search_query_args", $search_query_args ) );

		$wp_query = $search_query;

		ob_start(); global $abbey_query; global $count;	?>
			
			<?php if ( $search_query->have_posts() ) : abbey_setup_query(); $count = 0; ?>
				
				<div class="col-md-3" id="search-results-summary">
					<ul class="list-group">
						<?php do_action( "abbey_search_page_summary", $abbey_query ); ?>
					</ul>
				</div>

				<div id="search-results" class="col-md-6 col-md-offset-1">
					
					<?php while ( $search_query->have_posts() ) : $search_query->the_post(); $count++; ?>
					
						<?php get_template_part("templates/content", "search"); ?>

					<?php endwhile; ?> 

				</div>

		
			<?php else : get_template_part("templates/content", "archive-none"); endif; ?>

			<?php wp_reset_postdata(); ?>

		<?php $content = ob_get_clean(); 

		echo $content;

		wp_die();
	}


}

new Abbey_Ajax_Search();