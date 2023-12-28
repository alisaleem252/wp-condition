    <div class="wpcond_tab_child" id="ps_bestpractices" style="display:none">
		<h2 class="aligncenter">Best Practices</h2>
        <p>
        <canvas id="chart_bestPractices"></canvas>
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

            });
			</script>
        </p>

        <h3>USER EXPERIENCE</h3>
<?php 
		
		foreach ($result['lighthouseResult']['categories']['best-practices']['auditRefs'] as $audits_arr) {
			if(isset($audits_arr['group']) && $audits_arr['group'] == 'best-practices-ux' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
				<div id="perf_opportun_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
					<div class="postbox-header">
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
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
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
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
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
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
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
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
    </div> <!-- #ps_bestpractices -->