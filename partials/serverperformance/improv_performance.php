    <h3><?php esc_html_e('Improve Performance','wpcondtxtdmn');?></h3> 
    <p>
        <a href="<?php echo wpcondi_serviceURL ;?>" class="button-primary"><?php esc_html_e('Order Services','wpcondtxtdmn');?></a>
    </p>
    <p><a class="reset button-primary" style="background: #d83a3a; border-color: #d83a3a;" href="<?php echo add_query_arg( 'reset_wpfixit_con_stats', 1 ); ?>"><?php esc_html_e('Reset Statistics','wpcondtxtdmn');?></a></p>
    <p><?php esc_html_e('This table also displays 404 urls of not found resoures, It captures only the last 70 requests','wpcondtxtdmn');?>.</p>
    <table>
    
    
    <?php foreach ($load_times as $loadtime) echo '<tr style="display: inline-grid;"><td>'.$loadtime['url'] . '</td></tr><tr><td><strong> '.$loadtime['time'].esc_html__(' Seconds','wpcondtxtdmn').'</strong></td></tr>';?>
    </table>