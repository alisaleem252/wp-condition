<?php
/*
Plugin Name: WordPress Site Condition
Plugin URI: https://gigsix.com
Description: Display WP-Condition in Chart for Database Performance, Memory Performance, Site Performance, and Social Performance. Requires PHP 5.2.0+
Version: 1.5.0
Author: zinger252
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
		$this->average_option = is_admin() ? 'wpfixit_con_admin_load_times' : 'wpfixit_con_load_times';
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
		$timer_stop 		= timer_stop(0);
		$load_times			= array_filter( (array) get_option( $this->average_option ) );
		$load_times[]		= $timer_stop;
		// Update load times
		update_option( $this->average_option, $load_times );
		if(count($load_times) > 70)
		update_option( $this->average_option, array() );
		
	}
	function admin_menu() {
//		$this->display();
		add_menu_page( 'WPFIXIT', 'WP Condition', 'manage_options', 'wp-conditions', array( &$this, 'display' ), 'dashicons-chart-line', 99 );
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

	}

	/**
	 * enqueue function.
	 *
	 * @access public
	 */
	function enqueue() {
        wp_enqueue_style( 'wpfixit_con-style', plugins_url('style.css', __FILE__) );
		wp_enqueue_script( 'wpfixit_con-script', plugins_url('Chart.min.js', __FILE__) );
	}

	/**
	 * display function.
	 *
	 * @access public
	 */
	function display() {
		global $wpdb;
		// Get values we're displaying
		include( plugin_dir_path( __FILE__ ) . 'lib/social.php');         
		$obj=new WP_Condition_shareCount(site_url()); 
		$timer_stop 		= timer_stop(0);
		$query_count 		= get_num_queries();
		$memory_usage 		= round( $this->convert_bytes_to_hr( memory_get_usage() ), 2 );
		$memory_peak_usage 	= round( $this->convert_bytes_to_hr( memory_get_peak_usage() ), 2 );
		$memory_limit 		= round( $this->convert_bytes_to_hr( $this->let_to_num( WP_MEMORY_LIMIT ) ), 2 );
		$load_times			= array_filter( (array) get_option( $this->average_option ) );

		$load_times[]		= $timer_stop;

		// Update load times
	//	update_option( $this->average_option, $load_times );

		// Get average
		if ( sizeof( $load_times ) > 0 )
			$average_load_time = round( array_sum( $load_times ) / sizeof( $load_times ), 4 );

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
    data : { labels: [<?php foreach ($load_times as $loadtime) echo $loadtime . ','?>
										],
			datasets : [{ data : [ <?php foreach ($load_times as $loadtime) echo $loadtime . ','?>  ],
							backgroundColor : [<?php foreach ($load_times as $key => $loadtime) echo '"#D'.$key .'7041" , '?>],
							label : 'Seconds'
							
							
						}]
			}
	});
	</script>
                
                
                
                
        </td>
        <td>
   			<h1>Improve Performance</h1> 
            <p><a href="https://studio.envato.com/explore/wordpress-speed-optimization/47090-wordpress-speed-optimization-service" class="button-primary">Order Services</a></p>
            <p><a class="reset button-primary" style="background: #d83a3a; border-color: #d83a3a;" href="<?php echo add_query_arg( 'reset_wpfixit_con_stats', 1 ); ?>">Reset Statistics</a></p>
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
                
                <?php $social_counts = $obj->get_social_counts() ;?>
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

<td></td>

        
        </tr>
        
        </tbody>
        </table>

		</div>
        </div>
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