<?php
?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>
            Last Month (30 days before)<br/>
            <i style="font-size: 11px;">< <?=date("d-m 00:00:00", strtotime('-30 days'))?></i>
        </th>
        <th>
            Yesterday (1 day before)<br/>
            <i style="font-size: 11px;"><?=date("d-m 00:00:00", strtotime('-1 days'))?> -> <?=date("d-m 23:59:59", strtotime('-1 days'))?></i>
        </th>
        <th>
            Total (today)<br/>
            <i style="font-size: 11px;"><?=date("d-m 00:00:00", time())?> -> <?=date("d-m 23:59:59", time())?></i>
        </th>
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