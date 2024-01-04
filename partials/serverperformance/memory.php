    <h3><?php esc_html_e('Memory Usage','wpcondtxtdmn');?>:</h3>
    <p><?php printf( __( '%s out of %s MB (%s) memory used.', 'wpfixit_con' ), $memory_usage, $memory_limit, round( ( $memory_usage / $memory_limit ), 2 ) * 100 . '%' ); ?></p>
    <p><canvas id="dbperform"></canvas></p>
    <script>
	new Chart(document.getElementById("dbperform") ,{	type: 'pie', 
						data: {	labels: [
											'<?php esc_html_e('Limit','wpcondtxtdmn');?>',
											'<?php esc_html_e('Used','wpcondtxtdmn');?>'
										],
								datasets: [{ data: [<?php echo $memory_limit ?>, <?php echo $memory_usage ?>],
											 backgroundColor: ['lightgreen','orange'],
											 
											 }] 
											 } }
					);
    </script>