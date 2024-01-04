    <div class="wpcond_tab_child" id="ps_performance">
        <h2 class="aligncenter"><?php esc_html_e('Performance','wpcondtxtdmn');?></h2>
        <h3><?php esc_html_e('Core Web Assessment','wpcondtxtdmn');?></h3>
        <table class="wp-list-table widefat fixed striped">
			<tr>
				<th valign="top">
        <canvas id="chart_clss"></canvas>
                <script>
                        
                    var clss = document.getElementById("chart_clss"); // CUMULATIVE_LAYOUT_SHIFT_SCORE
                    var clsstext = '<?php echo $clss_meval_str?>';

                    var clssdata = {
                    labels: ["<?php esc_html_e('CUMULATIVE LAYOUT SHIFT SCORE','wpcondtxtdmn');?>: ( "+clsstext+" )"],
                    datasets: [{
                        label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: '<?php esc_html_e('Average','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                    var ttfbtext = '<?php echo $ttfb_meval_str?> <?php esc_html_e('s','wpcondtxtdmn');?>';

                    var ttfbdata = {
                    labels: ['<?php esc_html_e('EXPERIMENTAL TIME TO FIRST BYTE','wpcondtxtdmn');?>: ( '+ttfbtext+' )'],
                    datasets: [{
                        label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label:'<?php esc_html_e('Average','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                        var fcptext = '<?php echo $fcp_meval_str?> <?php esc_html_e('s','wpcondtxtdmn');?>';

                        var fcpdata = {
                        labels: ['<?php esc_html_e('FIRST CONTENTFUL PAINT','wpcondtxtdmn');?>: ( '+fcptext+' )'],
                        datasets: [{
                            label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
                            backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                        }, {
                            label: '<?php esc_html_e('Average','wpcondtxtdmn');?>',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
                            backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                        }, {
                            label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                            data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
                            backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                        },
                        {
                            label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                    var fidtext = '<?php echo $fid_meval_str?> <?php esc_html_e('ms','wpcondtxtdmn');?>';

                    var fiddata = {
                    labels: ['<?php esc_html_e('FIRST INPUT DELAY','wpcondtxtdmn');?>: ( '+fidtext+' )'],
                    datasets: [{
                        label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: '<?php esc_html_e('Average','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                    var itnptext = '<?php echo $itnp_meval_str?> <?php esc_html_e('ms','wpcondtxtdmn');?>';


                    var itnpdata = {
                    labels: ['<?php esc_html_e('INTERACTION TO NEXT PAINT','wpcondtxtdmn');?>: ( '+itnptext+' )'],
                    datasets: [{
                        label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: '<?php esc_html_e('Average','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                    var lcptext = '<?php echo $lcp_meval_str?> <?php esc_html_e('s','wpcondtxtdmn');?>';

                    var lcpdata = {
                    labels: ['<?php esc_html_e('LARGEST CONTENTFUL PAINT','wpcondtxtdmn');?>: ( '+lcptext+' )'],
                    datasets: [{
                        label: '<?php esc_html_e('Fast','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][0]['max'])?>'],
                        backgroundColor: 'rgba(11, 156, 49, 0.7)', // green
                    }, {
                        label: '<?php esc_html_e('Average','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][1]['max'])?>'],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Yellow
                    }, {
                        label: '<?php esc_html_e('Slow','wpcondtxtdmn');?>',
                        data: ['<?php echo ($result['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'][2]['min'])?>'],
                        backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red
                    },
                    {
                        label: '<?php esc_html_e('Exists','wpcondtxtdmn');?>',
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
                <h3><?php esc_html_e('Diagnose Performance','wpcondtxtdmn');?></h3>
				    <p>	
						<canvas id="chart_performance"></canvas>
							<script>
					
								var perf = document.getElementById("chart_performance"); 
								var perftext = '<?php echo ($result['lighthouseResult']['categories']['performance']['score'])*100 ?>%';

								
								new Chart(perf, {
								type: 'doughnut',
								data: {
								labels: ["<?php esc_html_e('Performance','wpcondtxtdmn');?>","<?php esc_html_e('Less','wpcondtxtdmn');?>"],
								datasets: [{
									label: "<?php esc_html_e('Performance','wpcondtxtdmn');?>",
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
				<p><?php echo $result['lighthouseResult']['i18n']['rendererFormattedStrings']['varianceDisclaimer']?></p>
				<p><?php esc_html_e('First Contentful Paint','wpcondtxtdmn');?>: <?php echo ($result['lighthouseResult']['audits']['first-contentful-paint']['displayValue'])?></p>
				<p><?php esc_html_e('Largest Contentful Paint','wpcondtxtdmn');?>: <?php echo ($result['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'])?></p>
				<p><?php esc_html_e('Total Blocking Time','wpcondtxtdmn');?>: <?php echo ($result['lighthouseResult']['audits']['total-blocking-time']['displayValue'])?></p>
				<p><?php esc_html_e('Cumulative Layout Shift','wpcondtxtdmn');?>: <?php echo ($result['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'])?></p>
				<p><?php esc_html_e('Speed Index','wpcondtxtdmn');?>: <?php echo ($result['lighthouseResult']['audits']['speed-index']['displayValue'])?></p>

				
				
	<h3><?php esc_html_e('OPPORTUNITIES','wpcondtxtdmn');?></h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && $audits_arr['details']["type"] == 'opportunity' && trim($audits_arr['score']) != '' && $audits_arr['score'] <= 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title'],'wpcondtxtdmn');?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score']  ?></strong></p>
						<p><?php esc_html_e($audits_arr['description'],'wpcondtxtdmn');?></p>
					</div>
				</div>
<?php
			}
		}
	?>


	<h3><?php esc_html_e('DIAGNOSTICS','wpcondtxtdmn');?></h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) && ($audits_arr['details']["type"] == 'table' || $audits_arr['details']["type"] == 'criticalrequestchain') && trim($audits_arr['score']) != 1){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title'],'wpcondtxtdmn');?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
						<p><?php esc_html_e($audits_arr['description'],'wpcondtxtdmn');?></p>
					</div>
				</div>
<?php
			}
		}
	?>



	<h3><?php esc_html_e('PASSED AUDITS','wpcondtxtdmn');?></h3>
	<?php 
		
		foreach ($result['lighthouseResult']["audits"] as $audits_key => $audits_arr) {
			if(isset($audits_arr['details']["type"]) &&  trim($audits_arr['score']) != '' && $audits_arr['score'] > 0.9){?>
				<div id="perf_opportun_<?php echo $audits_key ?>" class="postbox closed">
					<div class="postbox-header">
						<strong class="hndle ui-sortable-handle">&nbsp; <?php esc_html_e($audits_arr['title'],'wpcondtxtdmn');?></strong>
						<button type="button" class="handlediv">&vArr;</button>
					</div>
					<div class="inside">
						<p><strong><?php echo isset($audits_arr['displayValue']) ? $audits_arr['displayValue'] : 'Score: '.$audits_arr['score'] ?></strong></p>
						<p><?php esc_html_e($audits_arr['description'],'wpcondtxtdmn');?></p>

					</div>
				</div>
<?php
			}
		}
	?>



				
    </div><!-- #ps_performance -->