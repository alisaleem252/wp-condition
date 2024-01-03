<h3 class="expand-all">Peak Memory Usage:</h3>
<p><?php printf( __( 'Peak memory usage %s MB.', 'wpfixit_con' ), $memory_peak_usage ); ?></p>
<p><canvas id="peakmemory"></canvas></>


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