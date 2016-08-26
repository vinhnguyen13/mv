<?php
?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th title="From <?=date("d-m-Y 00:00:00", strtotime('-30 days'))?> To <?=date("d-m-Y 00:00:00", time())?>">Last Month (30 days before)</th>
        <th title="From <?=date("d-m-Y 00:00:00", strtotime('-1 days'))?> To <?=date("d-m-Y 23:59:59", strtotime('-1 days'))?>">Yesterday (1 day before)</th>
        <th title="From <?=date("d-m-Y 00:00:00", time())?> To <?=date("d-m-Y 23:59:59", time())?>">Total (today)</th>
    </tr>
    </thead>
    <tbody style="font-size: 12px;">
    <?php
    if(!empty($statistic)) {
        foreach($statistic as $key=>$value) {
            ?>
            <tr>
                <td><i><b><?=$key?></b></i></td>
                <td><?=$value[0]?></td>
                <td><?=$value[1]?></td>
                <td><?=$value[2]?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>