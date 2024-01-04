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

<p><?php echo wpcondi_readmein8($result['lighthouseResult']['categories']['accessibility']['description'])?></p>
				
                <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'table' &&  isset($audits_arr['score']) && $audits_arr['score'] == 0){?>
                            <div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
                                <div class="postbox-header" <?php echo ($audits_arr['score'] >= 0.9 ? 'style="background:orange;color:white;" ': ($audits_arr['score'] <= 0.9 ? 'style="background:#992a2a;color:white;"' : '') );?>>
                                    <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
                                    <button type="button" class="handlediv">&vArr;</button>
                                </div>
                                <div class="inside">
                                    <p><?php echo wpcondi_readmein8($audits_arr['description']) ?></p>
                                    <?php 
                                        if(isset($audits_arr['details']['items'])){
                                            echo '<p>';
                                            echo $audits_key;
                                            foreach($audits_arr['details']['items'] as $item){
                                            foreach($item as $key => $value){
                                                    echo '<p>';
                                                    echo '<strong>'.$key.'</strong> : '.(is_array($value) ? esc_html(json_encode($value)) : $value);
                                                    echo '</p>';
                                            }
                                            echo '<hr>';
                                            }
                                            echo '</p>';
                                        };
                                        ?>
                                </div>
                            </div>
            <?php
                        }
                    }
                ?>
            
            
            <h3 class="expand-all">ADDITIONAL ITEMS TO MANUALLY CHECK</h3>
            <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'manual'){?>
                            <div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
                                <div class="postbox-header" style="background:orange;color:white;">
                                    <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
                                    <button type="button" class="handlediv">&vArr;</button>
                                </div>
                                <div class="inside">
                                    <p><?php echo wpcondi_readmein8($audits_arr['description']) ?></p>
                                    <?php 
                                        if(isset($audits_arr['details']['items'])){
                                            echo '<p>';
                                            echo $audits_key;
                                            foreach($audits_arr['details']['items'] as $item){
                                            foreach($item as $key => $value){
                                                    echo '<p>';
                                                    echo '<strong>'.$key.'</strong> : '.(is_array($value) ? esc_html(json_encode($value)) : $value);
                                                    echo '</p>';
                                            }
                                            echo '<hr>';
                                            }
                                            echo '</p>';
                                        };
                                        ?>
                                </div>
                            </div>
            <?php
                        }
                    }
                ?>
            
            <h3 class="expand-all">NOT APPLICABLE</h3>
            <?php 
                    
                    foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                        if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'notApplicable'){?>
                            <div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
                                <div class="postbox-header" >
                                    <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
                                    <button type="button" class="handlediv">&vArr;</button>
                                </div>
                                <div class="inside">
                                    <p><?php
                                    //var_dump($audits_arr['description']);
                                    echo wpcondi_readmein8($audits_arr['description']) ?></p>
                                    <?php 
                                    if(isset($audits_arr['details']['items'])){
                                        echo '<p>';
                                        echo $audits_key;
                                        foreach($audits_arr['details']['items'] as $item){
                                        foreach($item as $key => $value){
                                                echo '<p>';
                                                echo '<strong>'.$key.'</strong> : '.(is_array($value) ? esc_html(json_encode($value)) : $value);
                                                echo '</p>';
                                        }
                                        echo '<hr>';
                                        }
                                        echo '</p>';
                                    };
                                    ?>
                                </div>
                            </div>
            <?php
                        }
                    }
                ?>


<h3>Mobile</h3>
    <iframe width="100%" height="1000px" onload="this.height=this.contentWindow.document.body.scrollHeight;" src="https://googlechrome.github.io/lighthouse/viewer/?psiurl=<?php echo urlencode($siteurl) ?>&strategy=mobile&category=accessibility&locale=en"></iframe>


    </div> <!--#ps_accessibility-->