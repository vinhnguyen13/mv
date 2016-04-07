<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/6/2016 4:12 PM
 */


?>
<ul class="list-reivew">
    <?php
    if(count($reviews) > 0){
        foreach($reviews as $review) {
            ?>
            <li class="<?=$review->user_id?>">
                <div class="stars">
                    <span class="rateit rating-review" data-rateit-value="<?=$review->rating?>" data-rateit-ispreset="true" data-rateit-readonly="true"></span>
                </div>
                <p class="infor-user-review">
                    <a href="/<?=$review->username?>"><?=$review->name?></a><?= date("d/m/Y h:iA", $review->created_at) ?> |
                    <?=$review->type==1 ? \frontend\models\UserReview::TYPE_1 : \frontend\models\UserReview::TYPE_2 ?>
                </p>
                <p><?=$review->description?></p>
            </li>
            <?php
        }
    } else { ?>
        <li>
            <p>Review not found.</p>
        </li>
    <?php } ?>
</ul>
<!--div class="text-right">
    <a href="#" class="color-cd fs-13 font-600 read_more">Read more</a>
</div-->
<script>
    $(function () { 
        $('.rating-review').rateit();
    });
</script>