    <h3>Improve Performance</h3> 
    <p>
        <a href="https://studio.envato.com/explore/wordpress-speed-optimization/47090-wordpress-speed-optimization-service" class="button-primary">Order Services</a>
        # <?php echo uniqid('hash_')?>
    </p>
    <p><a class="reset button-primary" style="background: #d83a3a; border-color: #d83a3a;" href="<?php echo add_query_arg( 'reset_wpfixit_con_stats', 1 ); ?>">Reset Statistics</a></p>
    <p>This table also displays 404 urls of not found resoures, It captures only the last 70 requests.</p>
    <table>
    
    
    <?php foreach ($load_times as $loadtime) echo '<tr style="display: inline-grid;"><td>'.$loadtime['url'] . '</td></tr><tr><td><strong> '.$loadtime['time']. ' Seconds</strong></td></tr>';?>
    </table>