    <div class="wpcond_tab_child" id="ps_performance">
        <h2 class="aligncenter">Performance</h2>
        <h3 class="expand-all">Core Web Assessment</h3>
        <table class="wp-list-table widefat fixed striped">
			<tr>
				<th valign="top">
        <canvas id="chart_clss"></canvas>
                <script>
                        
                    var clss = document.getElementById("chart_clss"); // CUMULATIVE_LAYOUT_SHIFT_SCORE
                    var clsstext = '<?php echo $clss_meval_str?>';

                    var clssdata = {
                    labels: ["CUMULATIVE LAYOUT SHIFT SCORE: ( "+clsstext+" )"],
                    datasets: [{
                        label: 'Fast',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: 'Average',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: 'Slow',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: 'Current',
                        data: ['<?php echo $clss_meval ?>'],
                        backgroundColor: 'rgba(100,100,100,0.7)', // gray
                    }
                    ]
                    };

                    var clss_config = {
                        type: 'bar',
                        data: clssdata,
                        options: {
                            // title: {
                            // 		 display: true,
                            // 		 text: clsstext
                            // },
                            scales: {
                                yAxes: [{
                                        ticks: {
                                                beginAtZero: true
                                                }
                                        }]

                            }
                        }
                    };

                    new Chart(clss, clss_config);
                </script>
                </th>
                <th valign="top">
                
                <canvas id="chart_ttfb" ></canvas>
                <script>
                        
                    var ttfb = document.getElementById("chart_ttfb");
                    var ttfbtext = '<?php echo $ttfb_meval_str?> s';

                    var ttfbdata = {
                    labels: ['EXPERIMENTAL TIME TO FIRST BYTE: ( '+ttfbtext+' )'],
                    datasets: [{
                        label: 'Fast',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: 'Average',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: 'Slow',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: 'Current',
                        data: ['<?php echo $ttfb_meval ?>'],
                        backgroundColor: 'rgba(100,100,100,0.7)', // gray
                    }
                    ]
                    };

                    var ttfb_config = {
                        type: 'bar',
                        data: ttfbdata,
                        options: {			
                            // title: {
                            // 		 display: true,
                            // 		 text: ttfbtext
                            // },
                            scales: {
                                yAxes: [{
                                        ticks: {
                                                beginAtZero: true
                                                }
                                        }]
                            }
                        }

                    };

                    new Chart(ttfb, ttfb_config);

                </script>
                </th>
            </tr>
            <tr>
                <th valign="top">
                
                <canvas id="chart_fcp" ></canvas>
                    <script>
            
                        var fcp = document.getElementById("chart_fcp"); 
                        var fcptext = '<?php echo $fcp_meval_str?> s';

                        var fcpdata = {
                        labels: ['FIRST CONTENTFUL PAINT: ( '+fcptext+' )'],
                        datasets: [{
                            label: 'Fast',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
                            backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                        }, {
                            label: 'Average',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
                            backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                        }, {
                            label: 'Slow',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
                            backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                        },
                        {
                            label: 'Current',
                            data: ['<?php echo $fcp_meval ?>'],
                            backgroundColor: 'rgba(100,100,100,0.7)', // gray
                        }
                        ]
                        };

                        var fcp_config = {
                            type: 'bar',
                            data: fcpdata,
                            options: {
                                // title: {
                                // 		 display: true,
                                // 		 text: fcptext
                                // },
                                scales: {
                                    yAxes: [{
                                            ticks: {
                                                    beginAtZero: true
                                                    }
                                            }]

                                }
                            }
                        };

                        new Chart(fcp, fcp_config);
                </script>
                </th>
                <th valign="top">
                
                <canvas id="chart_fid" ></canvas>
                <script>
                        
                    var fid = document.getElementById("chart_fid"); 
                    var fidtext = '<?php echo $fid_meval_str?> ms';

                    var fiddata = {
                    labels: ['FIRST INPUT DELAY: ( '+fidtext+' )'],
                    datasets: [{
                        label: 'Fast',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: 'Average',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: 'Slow',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: 'Current',
                        data: ['<?php echo $fid_meval ?>'],
                        backgroundColor: 'rgba(100,100,100,0.7)', // gray
                    }
                    ]
                    };

                    var fid_config = {
                        type: 'bar',
                        data: fiddata,
                        options: {
                            // title: {
                            // 		 display: true,
                            // 		 text: fidtext
                            // },
                            scales: {
                                yAxes: [{
                                        ticks: {
                                                beginAtZero: true
                                                }
                                        }]

                            }
                        }
                    };

                    new Chart(fid, fid_config);

                </script>
                </th>
            </tr>
            <tr>
                <th valign="top">
                
                <canvas id="chart_itnp" ></canvas>
                <script>
        
                    var itnp = document.getElementById("chart_itnp"); 
                    var itnptext = '<?php echo $itnp_meval_str?> ms';


                    var itnpdata = {
                    labels: ['INTERACTION TO NEXT PAINT: ( '+itnptext+' )'],
                    datasets: [{
                        label: 'Fast',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: 'Average',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: 'Slow',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: 'Current',
                        data: ['<?php echo $itnp_meval ?>'],
                        backgroundColor: 'rgba(100,100,100,0.7)', // gray
                    }
                    ]
                    };

                    var itnp_config = {
                        type: 'bar',
                        data: itnpdata,
                        options: {
                            // title: {
                            // 		 display: true,
                            // 		 text: itnptext
                            // },
                            scales: {
                                yAxes: [{
                                        ticks: {
                                                beginAtZero: true
                                                }
                                        }]

                            }
                        }

                    };

                    new Chart(itnp, itnp_config);
                </script>
                </th>
                <th valign="top">
                
                <canvas id="chart_lcp" ></canvas>
                <script>
                        
                    var lcp = document.getElementById("chart_lcp"); 
                    var lcptext = '<?php echo $lcp_meval_str?> s';

                    var lcpdata = {
                    labels: ['LARGEST CONTENTFUL PAINT: ( '+lcptext+' )'],
                    datasets: [{
                        label: 'Fast',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: 'Average',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: 'Slow',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: 'Current',
                        data: ['<?php echo $lcp_meval ?>'],
                        backgroundColor: 'rgba(100,100,100,0.7)', // gray
                    }
                    ]
                    };

                    var lcp_config = {
                        type: 'bar',
                        data: lcpdata,
                        options: {
                            // title: {
                            // 		 display: true,
                            // 		 text: lcptext
                            // },
                            scales: {
                                yAxes: [{
                                        ticks: {
                                                beginAtZero: true
                                                }
                                        }]

                            }
                        }
                    };

                    new Chart(lcp, lcp_config);
                </script>
                </th>
            </tr>
        </table>
                <h3 class="expand-all">Diagnose Performance</h3>
				    <p>	
						<canvas id="chart_performance"></canvas>
							<script>
					
								var perf = document.getElementById("chart_performance"); 
								var perftext = '<?php echo ($result['lighthouseResult']['categories']['performance']['score'])*100 ?>%';

								
								new Chart(perf, {
								type: 'doughnut',
								data: {
								labels: ["Performance","Less"],
								datasets: [{
									label: 'Performance',
									backgroundColor: ["green"],
									data: [<?php echo $result['lighthouseResult']['categories']['performance']['score']*100 ?>,100 - <?php echo $result['lighthouseResult']['categories']['performance']['score']*100 ?>]
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
								
									var textX = Math.round((width - ctx.measureText(perftext).width) / 2),
										textY = height / 1.7;
								
									ctx.fillText(perftext,textX,textY);
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

							}
							);

							
									
									
											</script>			
				</p>
				<p><?php echo wpcondi_readmein8($result['lighthouseResult']['i18n']['rendererFormattedStrings']['varianceDisclaimer'])?></p>
				<p>First Contentful Paint: <?php echo ($result['lighthouseResult']['audits']['first-contentful-paint']['displayValue'])?></p>
				<p>Largest Contentful Paint: <?php echo ($result['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'])?></p>
				<p>Total Blocking Time: <?php echo ($result['lighthouseResult']['audits']['total-blocking-time']['displayValue'])?></p>
				<p>Cumulative Layout Shift: <?php echo ($result['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'])?></p>
				<p>Speed Index: <?php echo ($result['lighthouseResult']['audits']['speed-index']['displayValue'])?></p>

				
				
	<h3 class="expand-all">OPPORTUNITIES</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'opportunity' && trim($audits_arr['score']) != '' && $audits_arr['score'] <= 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header" <?php echo ($audits_arr['score'] >= 0.9 ? 'style="background:orange;color:white;" ': ($audits_arr['score'] <= 0.9 ? 'style="background:#992a2a;color:white;"' : '') );?>>
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score']  ?></strong></p>
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


	<h3 class="expand-all">DIAGNOSTICS</h3>
	<?php 
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
            
			if(isset($audits_arr['details']["type"]) && ($audits_arr['details']["type"] == 'table' || $audits_arr['details']["type"] == 'criticalrequestchain') && trim($audits_arr['score']) != 1){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header" <?php echo ($audits_arr['score'] >= 0.9 ? 'style="background:orange;color:white;" ': ($audits_arr['score'] <= 0.9 ? 'style="background:#992a2a;color:white;"' : '') );?> >
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
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



	<h3 class="expand-all">PASSED AUDITS</h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) &&  trim($audits_arr['score']) != '' && $audits_arr['score'] > 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header" style="background:green;color:white;">
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title']) ?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
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

<h3>Mobile</h3>
    <iframe width="100%" height="1000px" onload="this.height=this.contentWindow.document.body.scrollHeight;" src="https://googlechrome.github.io/lighthouse/viewer/?psiurl=<?php echo urlencode($siteurl) ?>&strategy=mobile&category=performance&locale=en"></iframe>

				
    </div><!-- #ps_performance -->