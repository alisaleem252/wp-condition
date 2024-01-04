<h3><?php esc_html_e('Peak Memory Usage','wpcondtxtdmn');?>:</h3>
<p><?php printf( __( 'Peak memory usage %s MB.', 'wpfixit_con' ), $memory_peak_usage ); ?></p>
<p><canvas id="peakmemory"></canvas></p>


	<script>
	
	new Chart(document.getElementById("peakmemory") ,{	type: 'pie', 
						data: {	labels: [
											'<?php esc_html_e('Peak Usage','wpcondtxtdmn');?>',
											'<?php esc_html_e('Limit','wpcondtxtdmn');?>'
										],
								datasets: [{ data: [ <?php echo $memory_limit ?>, <?php echo $memory_peak_usage?>],
											 backgroundColor: ['lightgreen','orange'],
											 
											 }] 
											 } }
					);
					
	</script>