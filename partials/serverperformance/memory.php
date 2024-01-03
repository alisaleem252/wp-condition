    <h3 class="expand-all">Memory Usage:</h3>
    <p><?php printf( __( '%s out of %s MB (%s) memory used.', 'wpfixit_con' ), $memory_usage, $memory_limit, round( ( $memory_usage / $memory_limit ), 2 ) * 100 . '%' ); ?></p>
    <p><canvas id="dbperform"></canvas></p>
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