    <div class="wpcond_tab_child" id="ps_seo" style="display:none">
		<h2 class="aligncenter">SEO</h2>

        <p>
        <canvas id="chart_seo_sec" width="300px" height="200px"></canvas>
        <script>
            var seo_sec = document.getElementById("chart_seo_sec"); 
            var seotext = '<?php echo ($result['lighthouseResult']['categories']['seo']['score'])*100 ?>%';
            new Chart(seo_sec, {
            type: 'doughnut',
            data: {
            labels: ["SEO","Less"],
            datasets: [{
                label: 'SEO',
                backgroundColor: ["green"],
                data: [<?php echo $result['lighthouseResult']['categories']['seo']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['seo']['score']*100 ?>]
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
            
                var atextX = Math.round((width - ctx.measureText(seotext).width) / 2),
                    atextY = height / 1.7;
            
                ctx.fillText(seotext,atextX,atextY);
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


        <p><?php echo ($result['lighthouseResult']['categories']['seo']['description'])?></p>
        <?php if(isset($audits_arr['group']) && $audits_arr['group'] == 'seo-crawl'):?>
            <h3 class="expand-all">Crawling and Indexing</h3>
            <?php 
        endif; // seo-crawl

            foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
                if(isset($audits_arr['group']) && $audits_arr['group'] == 'seo-crawl' && (float) $result['lighthouseResult']["audits"][$audits_arr['id']]['score'] < 1.0){?>
                    <div id="seo_crawl_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
                        <div class="postbox-header">
                            <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
                            <button type="button" class="handlediv">&vArr;</button>
                        </div>
                        <div class="inside">
                            <p><?php echo wpcondi_readmein8($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
                            <?php 
                            if(isset($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'])){
                                echo '<p>';
                                echo $audits_arr['id'];
                                foreach($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'] as $item){
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
                
                foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
                    if($result['lighthouseResult']["audits"][$audits_arr['id']]['scoreDisplayMode'] == 'manual'){?>
                        <div id="seo_manualchk_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
                            <div class="postbox-header" style="background:orange;color:white;">
                                <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
                                <button type="button" class="handlediv">&vArr;</button>
                            </div>
                            <div class="inside">
                                <p><?php echo wpcondi_readmein8($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
                                <?php 
                                if(isset($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'])){
                                    echo '<p>';
                                    echo $audits_arr['id'];
                                    foreach($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'] as $item){
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




            <h3 class="expand-all">PASSED AUDITS</h3>
            <?php 
                
                foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
                    if(isset($audits_arr['scoreDisplayMode']) && $audits_arr['scoreDisplayMode'] == 'binary' && isset($audits_arr['score']) && $audits_arr['score'] == 1){?>
                        <div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
                            <div class="postbox-header" style="background:green;color:white;">
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

            foreach ($result['lighthouseResult']['categories']['seo']['auditRefs'] as $audits_arr) {
                if(isset($audits_arr['group']) && $audits_arr['group'] == 'seo-mobile' && $result['lighthouseResult']["audits"][$audits_arr['id']]['scoreDisplayMode'] == 'notApplicable'){?>
                    <div id="seo_crawl_<?php echo $result['lighthouseResult']["audits"][$audits_arr['id']]['id'] ?>" class="postbox closed">
                        <div class="postbox-header">
                            <strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($result['lighthouseResult']["audits"][$audits_arr['id']]['title']) ?></strong>
                            <button type="button" class="handlediv">&vArr;</button>
                        </div>
                        <div class="inside">
                            <p><?php echo wpcondi_readmein8($result['lighthouseResult']["audits"][$audits_arr['id']]['description']) ?></p>
                            <?php 
                            if(isset($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'])){
                                echo '<p>';
                                echo $audits_arr['id'];
                                foreach($result['lighthouseResult']["audits"][$audits_arr['id']]['details']['items'] as $item){
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
    <iframe width="100%" height="1000px" onload="this.height=this.contentWindow.document.body.scrollHeight;" src="https://googlechrome.github.io/lighthouse/viewer/?psiurl=<?php echo urlencode($siteurl) ?>&strategy=mobile&category=seo&locale=en"></iframe>

    </div> <!-- #ps_seo -->