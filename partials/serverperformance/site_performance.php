<h3 class="expand-all">Site Performance:</h3>
<p><?php printf( __( 'Average Server load time of %s (%s runs).', 'wpfixit_con' ), $average_load_time, sizeof( $load_times ) ); ?></p>
<p><canvas id="siperform"></canvas></p>


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