<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/6/2016 4:12 PM
 */

    if(count($reviews) > 0){
        foreach($reviews as $review) {
            ?>
            <li class="<?=$review->created_at?>">
                <div class="stars">
                    <span class="rateit rating-review" data-rateit-value="<?=$review->rating?>" data-rateit-ispreset="true" data-rateit-readonly="true"></span>
                </div>
                <p class="infor-user-review">
                    <a href="/<?=$review->username?>"><?=$review->name?></a><?= date("d/m/Y H:i", $review->created_at) ?> |
                    <?=$review->type==1 ? \frontend\models\UserReview::TYPE_1 : \frontend\models\UserReview::TYPE_2 ?>
                </p>
                <p><?=$review->description?></p>
            </li>
            <?php
        }
    } ?>

<script>
    $(function () {
        $('.rating-review').rateit();
    });
</script>