<?php

namespace vsoft\ec\models;

use common\models\EcStatisticView as StatisticView;
/**
 * This is the model class for table "ec_statistic_view".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $start_at
 * @property integer $end_at
 *
 * @property User $user
 */
class EcStatisticView extends StatisticView
{
    const CHARGE = 15;
    const LIMIT_DAY = 30;
}
