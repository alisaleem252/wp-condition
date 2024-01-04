<?php
defined( 'ABSPATH' ) || exit;

class WP_Page_Condition_Stats {

	private $average_option;

	/**
	 * Gets things started
	 */
	function __construct() {
		// Init
		add_action( 'init', array( &$this, 'init' ) );

		// Frontend
		add_action( 'wp_head', array( &$this, 'wp_head' ) );
		add_action( 'wp_footer', array( &$this, 'wp_footer' ) );

		// Backend
		add_action( 'admin_head', array( &$this, 'wp_head' ) );
		add_action( 'admin_footer', array( &$this, 'wp_footer' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

		// Enqueue
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue' ) );

		// Where to store averages
		$this->average_option = is_admin() ? 'wpfixit_con_load_times' : 'wpfixit_con_load_times';
	}

	/**
	 * init function.
	 *
	 * @access public
	 */
	function init() {
		load_plugin_textdomain( 'wpfixit_con', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		if ( isset( $_GET['reset_wpfixit_con_stats'] ) && $_GET['reset_wpfixit_con_stats'] == 1 ) {
			update_option( $this->average_option, array() );
			wp_safe_redirect(  wp_get_referer() );
			exit;
		} 
		
		if(is_admin())
		return;
		
		$timer_stop 		= timer_stop(0);
		$load_times			= array_filter( (array) get_option( $this->average_option ) );
		$load_times[]		= array('time' => $timer_stop,'url'=>$_SERVER['REQUEST_URI']);
		// Update load times
		update_option( $this->average_option, $load_times );
		if(count($load_times) > 70)
		update_option( $this->average_option, array() );
		
	}



	function admin_menu() {
//		$this->display();
		add_menu_page( 'WPFIXIT', 'WP Condition', 'manage_options', 'wp-conditions', array( &$this, 'display' ), 'dashicons-chart-line', 99 );
		add_submenu_page( 'wp-conditions','Settings WP Conditions', 'Settings WP Conditions', 'manage_options', 'wp-conditions-settings', array( &$this, 'wp_conditions_settingsdisplay'));
	}
	/**
	 * wp_head function.
	 *
	 * @access public
	 */
	function wp_head() {
/*		echo "<script type='text/javascript'>
			function wpfixit_con_hide(){
			   var wpplsDiv = document.getElementById('wpfixit_con');
			   wpplsDiv.style.display = 'none';
			}
		</script>"; */
	} 

	/**
	 * wp_footer function.
	 *
	 * @access public
	 */
	function wp_footer() {
	//	$this->display();
		wp_enqueue_script('dashboard');
		//wp_enqueue_script( 'jquery-ui-sortable');
	}

	/**
	 * enqueue function.
	 *
	 * @access public
	 */
	function enqueue() {
        wp_enqueue_style( 'wpfixit_con-style', wpcondi_URL.'style.css');
		wp_enqueue_script( 'wpfixit_con-script', wpcondi_URL.'Chart.min.js');
	}


	function wp_conditions_settingsdisplay() {
		if(isset($_POST['wp_conditions_settings']) && isset($_POST['wscwpc-Save_Settings']) && wp_verify_nonce( $_POST['wscwpc-Save_Settings'],'Save_Settings')) {
			update_option('wsc_wp_conditions_settings',$_POST['wp_conditions_settings']);
		}
		$wp_conditions_settings = get_option('wsc_wp_conditions_settings');
		?>
		<h2>Settings (WP Conditions)</h2>
		<div class="wrap">
			<form method="post">
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="wpcond_googleapis_key">Google API Key</label></th>
						<td>

							<input name="wp_conditions_settings[wpcond_googleapis_key]" type="text" id="wpcond_googleapis_key" value="<?php echo (isset($wp_conditions_settings['wpcond_googleapis_key']) ? $wp_conditions_settings['wpcond_googleapis_key'] : 'AIzaSyAtjindnYHHyOuf3vJA0GVCEde5CuKyRic')?>" class="regular-text" />
							<p>https://developers.google.com/speed/docs/insights/v5/get-started</p>

						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
				<?php  wp_nonce_field('Save_Settings', 'wscwpc-Save_Settings'); ?>
			</form>
		</div>

<?php
	} // func wp_conditions_settingsdisplay

	/**
	 * display function.
	 *
	 * @access public
	 */
	function display() {
		global $wpdb;
		$errDisp = false;
		$errMsg = '';
		$wp_conditions_settings = get_option('wsc_wp_conditions_settings',array());
		
		$date_y = date("Y");
		$date_m = date("m");
		$date_day = date("d");
		$key = isset($wp_conditions_settings['wpcond_googleapis_key']) && trim($wp_conditions_settings['wpcond_googleapis_key']) != '' ? $wp_conditions_settings['wpcond_googleapis_key'] : '';
		$siteurl = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost' ? 'https://developers.google.com/' : get_bloginfo('url').'/';  // 'https://developers.google.com'  'https://github.com' get_bloginfo('url').'/';
		$url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$siteurl&key=$key&category=accessibility&category=performance&category=pwa&category=best-practices&category=seo";

		$ps_data_fetched_1st = get_option("pagespeed_data_fetched_1st","no");

		$pso_dates_arr = get_option("pagespeedonline_dates_arr",array(date("Y_m_d")=>date("Y_m_d")));
		$pso_dates_arr = $pso_dates_arr && is_array($pso_dates_arr) ? $pso_dates_arr : array();

		$fetchdata_date = isset($_GET['fetchdata_date']) ? $_GET['fetchdata_date'] : date("Y_m_d");
	
		$result = get_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day);

		if($fetchdata_date == 'clear'){
			update_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day,array());
			update_option("pagespeedonline_dates_arr",array());
			echo "<meta http-equiv=refresh content=0;url=".admin_url('admin.php?page=wp-conditions')." />";
		}

		if($fetchdata_date == 'current' || $ps_data_fetched_1st == "no"){
			$args = array(
				'timeout'     => 2000,
			); 
			$pso_dates_arr[$date_y."_".$date_m."_".$date_day] = $date_y."_".$date_m."_".$date_day;
			$response = wp_remote_get($url,$args);
			if(is_wp_error($response)){
				$errDisp = true;
				$errMsg = $response->get_error_message();
			}
			else{
				update_option("pagespeed_data_fetched_1st","yes");
				$result = wp_remote_retrieve_body( $response );
				$result = json_decode($result,true);
			
				update_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day,$result);
				update_option("pagespeedonline_dates_arr",$pso_dates_arr);

				$result = get_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day);
			}

		}

		if(isset($_GET['fetchdata_date']) && ($_GET['fetchdata_date'] != 'clear' || $_GET['fetchdata_date'] != 'current')){
			$fetchdata_date_exp = explode('_',$fetchdata_date);
			$date_y = isset($fetchdata_date_exp[0]) ? $fetchdata_date_exp[0] : $date_y;
			$date_m = isset($fetchdata_date_exp[1]) ? $fetchdata_date_exp[1] : $date_m;
			$date_day = isset($fetchdata_date_exp[2]) ? $fetchdata_date_exp[2] : $date_day;
		}
		
		$result = get_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day);
		
		$pso_dates_arr = get_option("pagespeedonline_dates_arr",array(date("Y_m_d")=>date("Y_m_d")));
		$pso_dates_arr = $pso_dates_arr && is_array($pso_dates_arr) ? $pso_dates_arr : array(date("Y_m_d")=>date("Y_m_d"));

		//echo '<pre>';print_r($pso_dates_arr);echo '</pre>';

		// Get values we're displaying
		include( wpcondi_ABSPATH . '/lib/social.php');         
		$obj=new WP_Condition_shareCount(site_url()); 
		$timer_stop 		= timer_stop(0);
		$query_count 		= get_num_queries();
		$memory_usage 		= round( (int) $this->convert_bytes_to_hr( memory_get_usage() ), 2 );
		$memory_peak_usage 	= round( (int) $this->convert_bytes_to_hr( memory_get_peak_usage() ), 2 );
		$memory_limit 		= round( (int) $this->convert_bytes_to_hr( $this->let_to_num( WP_MEMORY_LIMIT ) ), 2 );
		$load_times			= array_filter( (array) get_option( $this->average_option ) );

		$load_times[]		= array('time' => $timer_stop,'url'=>$_SERVER['REQUEST_URI']);


		// Get average
		if ( sizeof( $load_times ) > 0 ){
			$sum = 0;
			foreach($load_times as $num => $load_time) {
				$sum += $load_time[ 'time' ];
			}
			$average_load_time = round( $sum / sizeof( $load_times ), 4 );
		}

		// Display the info
		?>
        <h1>WordPress Condition by <small>alisaleem252</small></h1>
		<?php 
				if($errDisp){?>
				<div id="message" class="error inline notice is-dismissible updated">
					<strong>Error: </strong> <p><?php echo $errMsg?></p>
				</div>

				<?php
				}
			?>

		<div class="wrap about__container maintabs" style="max-width:100%">
			
			<nav class="woo-nav-tab-wrapper nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
				<a href="javascript:void(0)" class="nav-tab maintab_child nav-tab-active" data-id="wpcond_Page_Speed">Page Speed</a>
				<a href="javascript:void(0)" class="nav-tab maintab_child" data-id="wpcond_Server_Performance">Server Performance</a>
			</nav>
			<div class="wpcond_maintab_child" id="wpcond_Page_Speed">
			<div class="aligncenter">&nbsp;</div><h2>Page Speed</h2><div class="aligncenter">&nbsp;</div>
				<?php require_once(wpcondi_ABSPATH.'/partials/pagespeed/fetched_records.php'); 

					if(isset($result['loadingExperience'])){
						$clss_meval = $result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'];
						$clss_meval_str = strlen($clss_meval) <= 3 ? $clss_meval/100 : $clss_meval/1000;

						$ttfb_meval = $result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['percentile'];
						$ttfb_meval_str = round($ttfb_meval/1000,1);

						$fcp_meval = $result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'];
						$fcp_meval_str = round($fcp_meval/1000,1);

						$fid_meval = $result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['percentile'];
						$fid_meval_str = $fid_meval;

						$itnp_meval = $result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['percentile'];
						$itnp_meval_str = $itnp_meval;

						$lcp_meval = $result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'];
						$lcp_meval_str =  round($lcp_meval/1000,1);

						?>

						<div class="wrap pagespeed_wrap about__container" style="max-width:100%">
							<nav class="about__header-navigation nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
								<a href="javascript:void(0)" class="nav-tab tab_child nav-tab-active" data-id="ps_performance">Performance (<?php echo ($result['lighthouseResult']['categories']['performance']['score'])*100 ?>%)</a>
								<a href="javascript:void(0)" class="nav-tab tab_child" data-id="ps_accessibility">Accessibility (<?php echo ($result['lighthouseResult']['categories']['accessibility']['score'])*100 ?>%)</a>
								<a href="javascript:void(0)" class="nav-tab tab_child" data-id="ps_bestpractices">Best Practices (<?php echo ($result['lighthouseResult']['categories']['best-practices']['score'])*100 ?>%)</a>
								<a href="javascript:void(0)" class="nav-tab tab_child" data-id="ps_seo">SEO (<?php echo ($result['lighthouseResult']['categories']['seo']['score'])*100 ?>%)</a>
							</nav>
							<?php

								require_once(wpcondi_ABSPATH.'/partials/pagespeed/performance.php');
								require_once(wpcondi_ABSPATH.'/partials/pagespeed/accessibility.php');
								require_once(wpcondi_ABSPATH.'/partials/pagespeed/bestpractices.php'); 
								require_once(wpcondi_ABSPATH.'/partials/pagespeed/seo.php'); 

							?>
						</div><!-- pagespeed_wrap -->
						<?php
					} // if(isset($result['id']) && isset($_GET['fetchdata_date']))

				?>

			</div><!-- #wpcond_Page_Speed -->
			<div class="wpcond_maintab_child" id="wpcond_Server_Performance" style="display:none">
				<div class="aligncenter">&nbsp;</div><h2>Server Performance</h2><div class="aligncenter">&nbsp;</div>
				<table class="wp-list-table widefat fixed striped">
					<tr>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/db_performance.php');?></th>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/site_performance.php');?></th>
					</tr>
					<tr>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/memory.php');?></th>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/peak_memory.php');?></th>
					</tr>
					<tr>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/social_performance.php');?></th>
						<th valign="top"><?php require_once(wpcondi_ABSPATH.'/partials/serverperformance/improv_performance.php');?></th>
					</tr>
				</table>
			</div><!-- #wpcond_Server_Performance -->
		</div><!-- about__container maintabs -->

		

		<script>
			jQuery(document).ready(function(){
				jQuery(document.body).on('click','.maintab_child',function(e){
					jQuery('.wpcond_maintab_child').hide();

					jQuery('.maintab_child').each( function() {
						jQuery(this).removeClass('nav-tab-active');
					});

					jQuery(this).addClass('nav-tab-active');

					var tabid = jQuery(this).data('id');

					jQuery('#'+tabid).show();
				}); // maintab_child

				jQuery(document.body).on('click','.tab_child',function(e){
					jQuery('.wpcond_tab_child').hide();

					jQuery('.tab_child').each( function() {
						jQuery(this).removeClass('nav-tab-active');
					});

					jQuery(this).addClass('nav-tab-active');

					var ch_tabid = jQuery(this).data('id');

					jQuery('#'+ch_tabid).show();
				}); // tab_child
			}); // jQuery(document).ready(function()


            // Expand all link click event
            jQuery('.expand-all').on('click', function(e) {
                e.preventDefault();
                jQuery('.postbox').toggleClass('closed');
            });
    
		</script>
		<?php
	}

	/**
	 * let_to_num function.
	 *
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer
	 *
	 * @access public
	 * @param $size
	 * @return int
	 */
	function let_to_num( $size ) {
	    $l 		= substr( $size, -1 );
	    $ret 	= substr( $size, 0, -1 );
	    switch( strtoupper( $l ) ) {
		    case 'P':
		        $ret *= 1024;
		    case 'T':
		        $ret *= 1024;
		    case 'G':
		        $ret *= 1024;
		    case 'M':
		        $ret *= 1024;
		    case 'K':
		        $ret *= 1024;
	    }
	    return $ret;
	}

	/**
	 * convert_bytes_to_hr function.
	 *
	 * @access public
	 * @param mixed $bytes
	 */
	function convert_bytes_to_hr( $bytes ) {
		$units = array( 0 => 'B', 1 => 'kB', 2 => 'MB', 3 => 'GB' );
		$log = log( $bytes, 1024 );
		$power = (int) $log;
		$size = pow(1024, $log - $power);
		return $size . $units[$power];
	}

}