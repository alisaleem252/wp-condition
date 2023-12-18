<?php
/*
Plugin Name: WordPress Site Condition
Plugin URI: https://gigsix.com
Description: Display WP-Condition in Chart for Database Performance, Memory Performance, Site Performance, and Social Performance. Requires PHP 5.2.0+
Version: 4.0.0
Author: alisaleem252
Author URI: http://thesetemplates.info
*/

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
        wp_enqueue_style( 'wpfixit_con-style', plugins_url('style.css', __FILE__) );
		wp_enqueue_script( 'wpfixit_con-script', plugins_url('Chart.min.js', __FILE__));
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
		$wp_conditions_settings = get_option('wsc_wp_conditions_settings');
		
		$date_y = date("Y");
		$date_m = date("m");
		$date_day = date("d");
		$key = isset($wp_conditions_settings['wpcond_googleapis_key']) && trim($wp_conditions_settings['wpcond_googleapis_key']) != '' ? $wp_conditions_settings['wpcond_googleapis_key'] : 'AIzaSyAtjindnYHHyOuf3vJA0GVCEde5CuKyRic';
		$siteurl = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost' ? 'https://developers.google.com/' : get_bloginfo('url').'/';  // 'https://developers.google.com'  'https://github.com' get_bloginfo('url').'/';
		$url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$siteurl&key=$key&category=accessibility&category=performance&category=pwa&category=best-practices&category=seo";

		//echo '<pre>';print_r($_SERVER);echo '</pre>';

		$pso_dates_arr = get_option("pagespeedonline_dates_arr");
		$pso_dates_arr = $pso_dates_arr && is_array($pso_dates_arr) ? $pso_dates_arr : array();

		$fetchdata_date = isset($_GET['fetchdata_date']) ? $_GET['fetchdata_date'] : '';
		if(trim($fetchdata_date) != '' && $fetchdata_date != 'current' && $fetchdata_date != 'clear'){
			//var_dump($fetchdata_date);

			$fetchdata_date_exp = explode('_',$fetchdata_date);
			$date_y = isset($fetchdata_date_exp[0]) ? $fetchdata_date_exp[0] : $date_y;
			$date_m = isset($fetchdata_date_exp[1]) ? $fetchdata_date_exp[1] : $date_m;
			$date_day = isset($fetchdata_date_exp[2]) ? $fetchdata_date_exp[2] : $date_day;
			$result = get_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day);
		}
		elseif($fetchdata_date == 'current'){
			$pso_dates_arr[$date_y."_".$date_m."_".$date_day] = $date_y."_".$date_m."_".$date_day;
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);
			
			$result = json_decode($result,true);
			
			update_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day,$result);
			update_option("pagespeedonline_dates_arr",$pso_dates_arr);

			$result = get_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day);
			echo "<meta http-equiv=refresh content=0;url=".admin_url('admin.php?page=wp-conditions')." />";

		}
		elseif($fetchdata_date == 'clear'){
			update_option("pagespeedonline_".$date_y."_".$date_m."_".$date_day,array());
			update_option("pagespeedonline_dates_arr",array());
		}
		$pso_dates_arr = get_option("pagespeedonline_dates_arr");
		$pso_dates_arr = $pso_dates_arr && is_array($pso_dates_arr) ? $pso_dates_arr : array();


		
		// Get values we're displaying
		include( plugin_dir_path( __FILE__ ) . 'lib/social.php');         
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
        <div id="wpfixit_container" style="width:100%">
		<div id="wpfixit_conditions">
        <table>
        <tbody cellspacing="">
        
        <tr>
        <td>
        
			
				<h2>Database Perfomance:</h2><p><?php printf( __( '%s queries in %s seconds.', 'wpfixit_con' ), $query_count, $timer_stop ); ?></p>
                <p>
                <?php if (empty( $load_times ))
						echo 'Reload this Page to see Chart';
						function wpo_fs_info($filesize)
				{
					$bytes = array(
							'B',
							'K',
							'M',
							'G',
							'T'
					);
					if ($filesize < 1024)
							$filesize = 1;
					for ($i = 0; $filesize > 1024; $i++)
							$filesize /= 1024;
					$wpo_fs_info['size'] = round($filesize, 3);
					$wpo_fs_info['type'] = $bytes[$i];
					return $wpo_fs_info;
				}
					$rows   = $wpdb->get_results("SHOW table STATUS");
					//var_dump($rows);
					$dbsize = 0;
					foreach ($rows as $row)
						{
							$dbsize += $row->Data_length + $row->Index_length;
						}
					$dbsize = wpo_fs_info($dbsize);
					echo 'Database Size '.$dbsize['size'].$dbsize['type'];
					
					
						?>
                </p>
                <canvas id="svperform" width="200" height="200"></canvas>
                <script>
		
	var ctx = document.getElementById("svperform");
	
	new Chart(ctx ,{	type: 'pie', 
						data: {	labels: [
											'Queries',
											'Time'
										],
								datasets: [{ data: [<?php echo $query_count ?>, <?php echo $timer_stop ?>],
											 backgroundColor: ['lightgray','black'],
											 
											 }] 
											 } }
					);

		/*
		 [
				{
					value: <?php echo $query_count ?>,
					color:"lightblue"
				},
				{
					value : ,
					color : "red"
				}};*/


                </script>
                </td>
        <td>
				<h2>Site Performance:</h2><p><?php printf( __( 'Average Server load time of %s (%s runs).', 'wpfixit_con' ), $average_load_time, sizeof( $load_times ) ); ?></p>
                <canvas id="siperform" height="200" width="200"></canvas>


	<script>
	
	new Chart(document.getElementById("siperform").getContext("2d"),{
	type: 'line',
    data : { labels: [<?php foreach ($load_times as $loadtime) echo $loadtime['time']  . ','?>
										],
			datasets : [{ data : [ <?php foreach ($load_times as $loadtime) echo $loadtime['time']. ','?>  ],
							backgroundColor : [<?php foreach ($load_times as $key => $loadtime) echo '"#D'.$key .'7041" , '?>],
							label : 'Seconds'
							
							
						},
						{ data : [ <?php foreach ($load_times as $loadtime) echo '"'.$loadtime['url'] .'" ,'?>  ],
							backgroundColor : [<?php foreach ($load_times as $key => $loadtime) echo '"#D'.$key .'7041" , '?>],
							label : 'URL'
							
							
						}]
			}
	});
	</script>
                
                
                
                
        </td>
        <td>
   			<h1>Improve Performance</h1> 
            <p><a href="https://studio.envato.com/explore/wordpress-speed-optimization/47090-wordpress-speed-optimization-service" class="button-primary">Order Services</a></p>
            <p><a class="reset button-primary" style="background: #d83a3a; border-color: #d83a3a;" href="<?php echo add_query_arg( 'reset_wpfixit_con_stats', 1 ); ?>">Reset Statistics</a></p>
			<p>This table also displays 404 urls of not found resoures, It captures only the last 70 requests.</p>
			<table>
			</tr>
			
			<?php foreach ($load_times as $loadtime) echo '<tr style="display: inline-grid;"><td>'.$loadtime['url'] . '</td><td> <strong> '.$loadtime['time']. '</strong> Seconds</td></tr>'?>
			</table>
        </td>
        </tr>
        
        <tr>
        <td>
                
				<h2>Memory Usage:</h2><p><?php printf( __( '%s out of %s MB (%s) memory used.', 'wpfixit_con' ), $memory_usage, $memory_limit, round( ( $memory_usage / $memory_limit ), 2 ) * 100 . '%' ); ?></p>
                
                <canvas id="dbperform" width="200" height="200"></canvas>
                <script>

	new Chart(document.getElementById("dbperform") ,{	type: 'pie', 
						data: {	labels: [
											'Limit',
											'Used'
										],
								datasets: [{ data: [<?php echo $memory_limit ?>, <?php echo $memory_usage ?>],
											 backgroundColor: ['lightgreen','orange'],
											 
											 }] 
											 } }
					);
					
		


                </script>

        </td>
        <td>

				<h2>Peak Memory Usage:</h2><p><?php printf( __( 'Peak memory usage %s MB.', 'wpfixit_con' ), $memory_peak_usage ); ?></p>
                
                <canvas id="peakmemory" height="200" width="200"></canvas>


	<script>
	
	new Chart(document.getElementById("peakmemory") ,{	type: 'pie', 
						data: {	labels: [
											'Peak Usage',
											'Limit'
										],
								datasets: [{ data: [ <?php echo $memory_limit ?>, <?php echo $memory_peak_usage?>],
											 backgroundColor: ['lightgreen','orange'],
											 
											 }] 
											 } }
					);
					
	</script>
                
			
			
            
                    </td>
                    <td>
                    
                    </td>
        </tr>
        
        <tr>
        <td colspan="2">
                
				<h2>Social Performance:</h2>
                <p>Using Sharethis Buttons</p>
                
                <?php $social_counts = 1;// $obj->get_social_counts() ;?>
                <canvas id="socialperform" height="300" width="600"></canvas>


<script>

new Chart(document.getElementById("socialperform") ,{	type: 'bar', 
						data: {	labels: [<?php foreach($social_counts['shares'] as $key => $social_count) echo '"'.$key.'",' ?> ],
								datasets: [{ data: [ <?php foreach($social_counts['shares'] as $key => $social_count) echo $social_count.', ' ?>],
												label: 'Sharethis Total Shares'
							//				 backgroundColor: ['lightgreen','orange'],
											 
											 }] 
											 } }
					);
					
				/*	
		var barChartData = {
			labels : ["Twitter","Facebook","LinkedIn","Google+","Delicious","Pinterest","Stumble"],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,1)",
					data : []
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : []
				}
			]
			
		}

	var myLine = new Chart(document.getElementById("socialperform")).Bar(barChartData);*/
	</script>                
                
                
                
        </td>

<td>&nbsp;</td>
</tr>
		<tr>
			<td colspan="3">
				<h2>Records Dates</h2>
				<p>
				<?php if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){
					foreach ($pso_dates_arr as $key => $value) {?>
						<a class="button button-secondary" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date='.$value) ?>">Fetch this date: <?php echo $value?></a> | 
						
						<?php
					}
				} ?>
				</p>
				<p>
					<a class="button button-primary" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date=current') ?>">Fetch Current Date</a> | 
			<?php if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){?>
					<a class="button" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date=clear') ?>" style="background:#d83a3a;border-color:#d83a3a;color:white">CLEAR DATA</a>
			<?php } //if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){} ?>
				</p>
			</td>
		</tr>
        

	<?php if(isset($result['id']) && isset($_GET['fetchdata_date'])){ 
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
	

	

		<tr>
			<td colspan="3">
			<div class="wrap wrap about__container" style="max-width:100%">
				<nav class="about__header-navigation nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
					<a href="javascript:void(0)" class="nav-tab" data-id="performance">Performance (<?php echo ($result['lighthouseResult']['categories']['performance']['score'])*100 ?>%)</a>
					<a href="javascript:void(0)" class="nav-tab" data-id="accessibility">Accessibility (<?php echo ($result['lighthouseResult']['categories']['accessibility']['score'])*100 ?>%)</a>
					<a href="javascript:void(0)" class="nav-tab" data-id="bestpractices">Best Practices (<?php echo ($result['lighthouseResult']['categories']['best-practices']['score'])*100 ?>%)</a>
					<a href="javascript:void(0)" class="nav-tab" data-id="seo">SEO (<?php echo ($result['lighthouseResult']['categories']['seo']['score'])*100 ?>%)</a>
				</nav>
				<div class="wpcond_api_data" id="performance">
					
						<h2 class="aligncenter">Performance</h2>
						
				<h3>Core Web Assessment</h3>
				<table> 
					<tr>
						<td>
							<canvas id="chart_clss" width="200" height="200"></canvas>
							<script>
									
								var clss = document.getElementById("chart_clss"); // CUMULATIVE_LAYOUT_SHIFT_SCORE
								var clsstext = '<?php echo $clss_meval_str?>';

								var clssdata = {
								labels: ["CUMULATIVE LAYOUT SHIFT SCORE: ( "+clsstext+" )"],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $clss_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var clss_config = {
									type: 'bar',
									data: clssdata,
									options: {
										// title: {
										// 		 display: true,
										// 		 text: clsstext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]

										}
									}
								};

								new Chart(clss, clss_config);

							</script>				
										</td>




										<td>
										<canvas id="chart_ttfb" width="200" height="200"></canvas>
											<script>
									
								var ttfb = document.getElementById("chart_ttfb");
								var ttfbtext = '<?php echo $ttfb_meval_str?> s';

								var ttfbdata = {
								labels: ['EXPERIMENTAL TIME TO FIRST BYTE: ( '+ttfbtext+' )'],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $ttfb_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var ttfb_config = {
									type: 'bar',
									data: ttfbdata,
									options: {			
										// title: {
										// 		 display: true,
										// 		 text: ttfbtext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]
										}
									}

								};

								new Chart(ttfb, ttfb_config);

											</script>				
										</td>




										<td>
										<canvas id="chart_fcp" width="200" height="200"></canvas>
											<script>
									
								var fcp = document.getElementById("chart_fcp"); 
								var fcptext = '<?php echo $fcp_meval_str?> s';

								var fcpdata = {
								labels: ['FIRST CONTENTFUL PAINT: ( '+fcptext+' )'],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $fcp_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var fcp_config = {
									type: 'bar',
									data: fcpdata,
									options: {
										// title: {
										// 		 display: true,
										// 		 text: fcptext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]

										}
									}
								};

								new Chart(fcp, fcp_config);

											</script>				
										</td>
									</tr>


							<!--

							SECOND ROW OF WEBSITE PERFORMANCE

							-->
								
									<tr>
										<td>
										<canvas id="chart_fid" width="200" height="200"></canvas>
							<script>
									
								var fid = document.getElementById("chart_fid"); 
								var fidtext = '<?php echo $fid_meval_str?> ms';

								var fiddata = {
								labels: ['FIRST INPUT DELAY: ( '+fidtext+' )'],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $fid_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var fid_config = {
									type: 'bar',
									data: fiddata,
									options: {
										// title: {
										// 		 display: true,
										// 		 text: fidtext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]

										}
									}
								};

								new Chart(fid, fid_config);

							</script>				
										</td>




										<td>
										<canvas id="chart_itnp" width="200" height="200"></canvas>
											<script>
									
								var itnp = document.getElementById("chart_itnp"); 
								var itnptext = '<?php echo $itnp_meval_str?> ms';


								var itnpdata = {
								labels: ['INTERACTION TO NEXT PAINT: ( '+itnptext+' )'],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $itnp_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var itnp_config = {
									type: 'bar',
									data: itnpdata,
									options: {
										// title: {
										// 		 display: true,
										// 		 text: itnptext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]

										}
									}

								};

								new Chart(itnp, itnp_config);

											</script>				
										</td>




										<td>
										<canvas id="chart_lcp" width="200" height="200"></canvas>
											<script>
									
								var lcp = document.getElementById("chart_lcp"); 
								var lcptext = '<?php echo $lcp_meval_str?> s';

								var lcpdata = {
								labels: ['LARGEST CONTENTFUL PAINT: ( '+lcptext+' )'],
								datasets: [{
									label: 'Fast',
									data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
									backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
								}, {
									label: 'Average',
									data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
									backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
								}, {
									label: 'Slow',
									data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
									backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
								},
								{
									label: 'Exists',
									data: ['<?php echo $lcp_meval ?>'],
									backgroundColor: 'rgba(100,100,100,0.7)', // gray
								}
								]
								};

								var lcp_config = {
									type: 'bar',
									data: lcpdata,
									options: {
										// title: {
										// 		 display: true,
										// 		 text: lcptext
										// },
										scales: {
											yAxes: [{
													ticks: {
															beginAtZero: true
															}
													}]

										}
									}
								};

								new Chart(lcp, lcp_config);

											</script>				
										</td>
									</tr>

<!--

SECTION START PERFORMANCE METRICS

 -->
   
 <tr>
			<td colspan="3">
				<h3>Diagnose Performance</h3>
				<p>	
						<canvas id="chart_performance" width="300px" height="200px"></canvas>
							<script>
					
								var perf = document.getElementById("chart_performance"); 
								var perftext = '<?php echo ($result['lighthouseResult']['categories']['performance']['score'])*100 ?>%';

								
								new Chart(perf, {
								type: 'doughnut',
								data: {
								labels: ["Performance","Less"],
								datasets: [{
									label: 'Performance',
									backgroundColor: ["green"],
									data: [<?php echo $result['lighthouseResult']['categories']['performance']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['performance']['score']*100 ?>]
								}]
								},
								plugins: [{
								beforeDraw: function(chart) {
									var width = chart.chart.width,
										height = chart.chart.height,
										ctx = chart.chart.ctx;
								
									ctx.restore();
									var fontSize = (height / 90).toFixed(2);
										ctx.font = fontSize + "em sans-serif";
										ctx.textBaseline = "middle";
								
									var textX = Math.round((width - ctx.measureText(perftext).width) / 2),
										textY = height / 1.7;
								
									ctx.fillText(perftext,textX,textY);
									ctx.save();
								}
							}],
								options: {
								legend: {
									display: true,
								},
								responsive: true,
								maintainAspectRatio: false,
								cutoutPercentage: 50
								}

							}
							);

							
									
									
											</script>			
				</p>
				<p><?php echo $result['lighthouseResult']['i18n']['rendererFormattedStrings']['varianceDisclaimer']?></p>
				<p>First Contentful Paint: <?php echo ($result['lighthouseResult']['audits']['first-contentful-paint']['displayValue'])?></p>
				<p>Largest Contentful Paint: <?php echo ($result['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'])?></p>
				<p>Total Blocking Time: <?php echo ($result['lighthouseResult']['audits']['total-blocking-time']['displayValue'])?></p>
				<p>Cumulative Layout Shift: <?php echo ($result['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'])?></p>
				<p>Speed Index: <?php echo ($result['lighthouseResult']['audits']['speed-index']['displayValue'])?></p>

				
				
	<h3>OPPORTUNITIES</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'opportunity' && trim($audits_arr['score']) != '' && $audits_arr['score'] <= 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score']  ?></strong></p>
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>


	<h3>DIAGNOSTICS</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && ($audits_arr['details']["type"] == 'table' || $audits_arr['details']["type"] == 'criticalrequestchain') && trim($audits_arr['score']) != 1){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>



	<h3>PASSED AUDITS</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) &&  trim($audits_arr['score']) != '' && $audits_arr['score'] > 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
						<p><?php esc_html_e($audits_arr['description']) ?></p>

					</div>
				</div>
<?php
			}
		}
	?>
		
			</td>
		</tr>

	</table>
				
</div>
	

<!-- 

Accessibility Section

-->

			<div class="wpcond_api_data" id="accessibility">
					
				<h2 class="aligncenter">Accessibility</h2>
				<table>
					
					<tr>
						<td>
							
						<canvas id="chart_accessibility" width="300px" height="200px"></canvas>
							<script>
					
								var accesss = document.getElementById("chart_accessibility"); 
								var accessstext = '<?php echo ($result['lighthouseResult']['categories']['accessibility']['score'])*100 ?>%';

								
								new Chart(accesss, {
								type: 'doughnut',
								data: {
								labels: ["Accessibility","Less"],
								datasets: [{
									label: 'Accessibility',
									backgroundColor: ["green"],
									data: [<?php echo $result['lighthouseResult']['categories']['accessibility']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['accessibility']['score']*100 ?>]
								}]
								},
								plugins: [{
								beforeDraw: function(chart) {
									var width = chart.chart.width,
										height = chart.chart.height,
										ctx = chart.chart.ctx;
								
									ctx.restore();
									var fontSize = (height / 90).toFixed(2);
										ctx.font = fontSize + "em sans-serif";
										ctx.textBaseline = "middle";
								
									var atextX = Math.round((width - ctx.measureText(accessstext).width) / 2),
										atextY = height / 1.7;
								
									ctx.fillText(accessstext,atextX,atextY);
									ctx.save();
								}
							}],
								options: {
								legend: {
									display: true,
								},
								responsive: true,
								maintainAspectRatio: false,
								cutoutPercentage: 50
								}

							}
							);

							
									
									
											</script>			
						</td>
					</tr>

				</table>
				<p><?php echo ($result['lighthouseResult']['categories']['accessibility']['description'])?></p>
				
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'table' &&  isset($audits_arr['score']) && $audits_arr['score'] == 0){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>


				<h3>ADDITIONAL ITEMS TO MANUALLY CHECK</h3>
<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'manual'){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>





		<h3>NOT APPLICABLE</h3>
<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'notApplicable'){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>
							
				</div>
					
		
<!-- 
	Best Practices Section
-->

				<div class="wpcond_api_data" id="bestpractices">
					
						<h2 class="aligncenter">Best Practices</h2>
						<table>
					
					<tr>
						<td>
							
						<canvas id="chart_bestPractices" width="300px" height="200px"></canvas>
							<script>
					
								var bestp = document.getElementById("chart_bestPractices"); 
								var bestptext = '<?php echo ($result['lighthouseResult']['categories']['best-practices']['score'])*100 ?>%';

								
								new Chart(bestp, {
								type: 'doughnut',
								data: {
								labels: ["Best Practices","Less"],
								datasets: [{
									label: 'Best Practices',
									backgroundColor: ["green"],
									data: [<?php echo $result['lighthouseResult']['categories']['best-practices']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['best-practices']['score']*100 ?>]
								}]
								},
								plugins: [{
								beforeDraw: function(chart) {
									var width = chart.chart.width,
										height = chart.chart.height,
										ctx = chart.chart.ctx;
								
									ctx.restore();
									var fontSize = (height / 90).toFixed(2);
										ctx.font = fontSize + "em sans-serif";
										ctx.textBaseline = "middle";
								
									var atextX = Math.round((width - ctx.measureText(bestptext).width) / 2),
										atextY = height / 1.7;
								
									ctx.fillText(bestptext,atextX,atextY);
									ctx.save();
								}
							}],
								options: {
								legend: {
									display: true,
								},
								responsive: true,
								maintainAspectRatio: false,
								cutoutPercentage: 50
								}

							}
							);

							
									
									
											</script>			
						</td>
					</tr>

				</table>





		<h3>USER EXPERIENCE</h3>
<?php 
		
		foreach ($result['lighthouseResult']['categories']['best-practices']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'best-practices-ux' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
				<div id="perf_opportun_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>






			<h3>Trust And Safety</h3>
<?php 
		
		foreach ($result['lighthouseResult']['categories']['best-practices']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'best-practices-trust-safety' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
				<div id="perf_opportun_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>








<h3>GENERAL</h3>
<?php 
		
		foreach ($result['lighthouseResult']['categories']['best-practices']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'best-practices-general' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
				<div id="perf_opportun_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>
	






	<h3>PASSED AUDITS</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'binary' && isset($audits_arr['score']) && $audits_arr['score'] == 1){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($audits_arr['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>
				</div>
						
		
<!-- 
	SEO Section
-->

				<div class="wpcond_api_data" id="seo">
					
						<h2 class="aligncenter">SEO</h2>
						<table>
					
					<tr>
						<td>
							
						<canvas id="chart_seo_sec" width="300px" height="200px"></canvas>
							<script>
					
								var seo_sec = document.getElementById("chart_seo_sec"); 
								var seotext = '<?php echo ($result['lighthouseResult']['categories']['seo']['score'])*100 ?>%';

								
								new Chart(seo_sec, {
								type: 'doughnut',
								data: {
								labels: ["SEO","Less"],
								datasets: [{
									label: 'SEO',
									backgroundColor: ["green"],
									data: [<?php echo $result['lighthouseResult']['categories']['seo']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['seo']['score']*100 ?>]
								}]
								},
								plugins: [{
								beforeDraw: function(chart) {
									var width = chart.chart.width,
										height = chart.chart.height,
										ctx = chart.chart.ctx;
								
									ctx.restore();
									var fontSize = (height / 90).toFixed(2);
										ctx.font = fontSize + "em sans-serif";
										ctx.textBaseline = "middle";
								
									var atextX = Math.round((width - ctx.measureText(seotext).width) / 2),
										atextY = height / 1.7;
								
									ctx.fillText(seotext,atextX,atextY);
									ctx.save();
								}
							}],
								options: {
								legend: {
									display: true,
								},
								responsive: true,
								maintainAspectRatio: false,
								cutoutPercentage: 50
								}

							}
							);

							
									
									
											</script>			
						</td>
					</tr>

				</table>
				<p><?php echo ($result['lighthouseResult']['categories']['seo']['description'])?></p>





		<h3>Crawling and Indexing</h3>
<?php 
		
		foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'seo-crawl' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
				<div id="seo_crawl_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>	


	<h3>ADDITIONAL ITEMS TO MANUALLY CHECK</h3>
	<?php 
			
			foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
				if($result['lighthouseResult']["audits"][$audits_arr['id']]['scoreDisplayMode'] == 'manual'){?>
					<div id="seo_manualchk_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
						<div class="postbox-header">
							<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
							<button type="button" class="handlediv">&vArr;</button>
						</div>
						<div class="inside">
							<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
						</div>
					</div>
	<?php
				}
			}
		?>	




	<h3>PASSED AUDITS</h3>
		<?php 
			
			foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
				if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'binary' && isset($audits_arr['score']) && $audits_arr['score'] == 1){?>
					<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
						<div class="postbox-header">
							<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></h4>
							<button type="button" class="handlediv">&vArr;</button>
						</div>
						<div class="inside">
							<p><?php esc_html_e($audits_arr['description']) ?></p>
						</div>
					</div>
	<?php
				}
			}
		?>
	



	<h3>NOT APPLICABLE</h3>
	<?php 
		
		foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'seo-mobile' && $result['lighthouseResult']["audits"][$audits_arr['id']]['scoreDisplayMode'] == 'notApplicable'){?>
				<div id="seo_crawl_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<h4 class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></h4>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
					</div>
				</div>
<?php
			}
		}
	?>	
				</div>
			<div>
				
			
			</td>
		</tr>





	





	<?php } // if(isset($result['id'])){ ?>
        </tbody>
        </table>

		</div>
    </div>

		<script>
			jQuery(document).ready(function(){
				jQuery('.wpcond_api_data').hide();
				jQuery(document.body).on('click','.nav-tab',function(e){
					jQuery('.wpcond_api_data').hide();

					jQuery('.nav-tab').each( function() {
						jQuery(this).removeClass('nav-tab-active');
					});

					jQuery(this).addClass('nav-tab-active');

					var tabid = jQuery(this).data('id');

					jQuery('#'+tabid).show();
				});
			}); // jQuery(document).ready(function()
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

$WP_Page_Condition_Stats = new WP_Page_Condition_Stats();
