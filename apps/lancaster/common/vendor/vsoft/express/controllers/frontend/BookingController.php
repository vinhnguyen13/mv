<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 9/18/2015 11:51 AM
 */
namespace vsoft\express\controllers\frontend;

use vsoft\express\models\LcBooking;
use vsoft\express\models\LcBuilding;
use Yii;
use yii\web\Controller;

class BookingController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionViewFloorByBuilding($id)
    {
        if ($id > 0) {
            $building = LcBuilding::find()->where(['id' => $id])->one();
            $floor = $building->floor;
            if ($building) {
//                echo "<option value='333'>$building->floor</option>";
                for ($x = 1; $x <= $floor; $x++) {
                    echo "<option value='$x'>$x</option>";
                }

            }
        }
//        else{
//            echo "<option>Cannot find floor number in building</option>";
//        }
    }

    public function actionBookingHotel()
    {
        $booking = new LcBooking();
        if(!empty($_POST)) {
            $post = Yii::$app->request->post();
//            date('Y-m-d H:i:s', strtotime('04/08/2010 10:22 am'));
            $str_in = strtotime($post["checkin"]);
            $str_out = strtotime($post["checkout"]);

            if($str_out > $str_in) {
                $booking->checkin = date('Y-m-d',$str_in);
                $booking->checkout = date('Y-m-d',$str_out);
            }
            else
            {
                Yii::$app->getSession()->setFlash('reError', 'Check out greater than!');
            }

            $booking->lc_building_id = $post["LcBooking"]["lc_building_id"];
            $booking->apart_type = $post["apart"];
            $booking->floorplan = $post["floor"];
            $booking->fullname = $post["fullname"];
            $booking->phone = $post["phone"];
            $booking->email = $post["email"];
            $booking->address = $post["address"];
            $booking->passport_no = $post["passport"];
            $booking->nationality = $post["nation"];
            $booking->info = $post["info"];
        }

        if($booking->save()) {
            if(!empty($booking->email)) {
                // call send mail method after click submit button
                $booking->sendBookingMail($booking);
            }
            Yii::$app->getSession()->setFlash('reSuccess', 'Create booking successfully.');
        }

        return $this->redirect(['/booking']);


    }

}