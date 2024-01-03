    <h3 class="expand-all">Social Performance:</h3>
    <p>Using Sharethis Buttons</p>
                
    <?php $social_counts = $obj->get_social_counts(); //1 ?>
    <p><canvas id="socialperform"></canvas></p>


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