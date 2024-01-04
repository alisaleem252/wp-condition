    <h3><?php esc_html_e('Database Perfomance','wpcondtxtdmn');?>:</h3>
	<?php printf( __( '%s queries in %s seconds.', 'wpfixit_con' ), $query_count, $timer_stop ); ?>
    <p>
    <?php if (empty( $load_times ))
             esc_html_e('Reload this Page to see Chart','wpcondtxtdmn');
            function wpo_fs_info($filesize)
    {
        $bytes = array(
                'B',
                'K',
                'M',
                'G',
                'T'
        );
        if ($filesize < 1024)
                $filesize = 1;
        for ($i = 0; $filesize > 1024; $i++)
                $filesize /= 1024;
        $wpo_fs_info['size'] = round($filesize, 3);
        $wpo_fs_info['type'] = $bytes[$i];
        return $wpo_fs_info;
    }
        $rows   = $wpdb->get_results("SHOW table STATUS");
        //var_dump($rows);
        $dbsize = 0;
        foreach ($rows as $row)
            {
                $dbsize += $row->Data_length + $row->Index_length;
            }
        $dbsize = wpo_fs_info($dbsize);
         esc_html_e('Database Size ','wpcondtxtdmn').$dbsize['size'].$dbsize['type'];
        
        
            ?>
    </p>
    <p><canvas id="svperform"></canvas></p>
    <script>
	    var ctx = document.getElementById("svperform");
        new Chart(ctx ,{	type: 'pie', 
                            data: {	labels: [
                                                '<?php esc_html_e('Queries','wpcondtxtdmn');?>',
                                                '<?php esc_html_e('Time','wpcondtxtdmn');?>'
                                            ],
                                    datasets: [{ data: [<?php echo $query_count ?>, <?php echo $timer_stop ?>],
                                                backgroundColor: ['lightgray','black'],
                                                
                                                }] 
                                                } }
                        );

		/*
		 [
				{
					value: <?php echo $query_count ?>,
					color:"lightblue"
				},
				{
					value : ,
					color : "red"
				}};*/


    </script>