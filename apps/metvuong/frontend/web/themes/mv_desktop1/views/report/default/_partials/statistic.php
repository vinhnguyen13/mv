<?php
?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($statistic)) {
        foreach($statistic as $key=>$stt) {
            ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $stt ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>