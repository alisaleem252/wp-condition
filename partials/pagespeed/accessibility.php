    <div class="wpcond_tab_child" id="ps_accessibility" style="display:none">		
		<h2 class="aligncenter">Accessibility</h2>
        <p><canvas id="chart_accessibility"></canvas></p>
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
            });
		</script>

<p><?php echo ($result['lighthouseResult']['categories']['accessibility']['description'])?></p>
				
                <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'table' &&  isset($audits_arr['score']) && $audits_arr['score'] == 0){?>
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
            
            
            <h3>ADDITIONAL ITEMS TO MANUALLY CHECK</h3>
            <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'manual'){?>
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
            
            <h3>NOT APPLICABLE</h3>
            <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'notApplicable'){?>
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
    </div> <!--#ps_accessibility-->