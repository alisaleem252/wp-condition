    <h4>Fetched Records</h4>

    <p>
    <?php 
    if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){
        foreach ($pso_dates_arr as $key => $value) {?>
            <a class="button button-secondary" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date='.$value) ?>">Display this date: <?php echo $value?> Data</a> | 
            <?php
        }
    } ?>
    </p>

	<p>
        <a class="button button-primary" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date=current') ?>">Fetch Today Data</a> | 
        <?php if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){?>
            <a class="button" href="<?php echo admin_url('admin.php?page=wp-conditions&fetchdata_date=clear') ?>" style="background:#d83a3a;border-color:#d83a3a;color:white">Clear Fetched Data</a>
        <?php } //if(is_array($pso_dates_arr) && count($pso_dates_arr) > 0){} ?>
    </p>