<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 6/24/2016 2:07 PM
 */

use yii\helpers\Url;
use yii\web\View;

if(!empty(Yii::$app->params['tutorial'])) {

    $cookies = Yii::$app->request->cookies;
    $cookie = $cookies->getValue('tutorial');
    $action = Yii::$app->controller->uniqueid . "/" . Yii::$app->controller->action->id;
//$tutorial_cookies = [
//    'site/index' => 'Homepage',
//    'dashboard/ad' => 'Dashboard',
//    'dashboard/statistics' => 'Statistic',
//    'ad/index1' => 'BuyListing',
//    'ad/index2' => 'RentListing',
//    'ad/post' => 'Post',
//    'payment/index' => 'Payment',
//];

    if ($action == 'site/index' && (!isset($cookie['Homepage']) || empty($cookie['Homepage']))) {
        $txtArr = [
            Yii::t("tutorial", "Ở phía trên bạn sẽ tìm thấy thanh Home Bar, thanh này sẽ luôn được hiển thị cho bạn và cho phép bạn nhanh chóng điều hướng đến tất cả các tính năng quan trọng của trang."),
            Yii::t("tutorial", "Bạn sẽ dễ dàng tìm kiếm các vấn đề liên quan đến bất động sản bằng thanh Tìm Kiếm Nhanh nằm ngay giữa trang chủ. Tiện ích này cho phép bạn nhanh chóng tìm kiếm thông tin hoặc sản phẩm bất động sản theo yêu cầu của bạn, thông qua vị trí (thành phố, quận, phường và đường) hoặc tên dự án, mã ID sản phẩm (Nếu bạn biết ID tin cần tìm, bạn cũng có thể gõ mã này như một phím tắt để đưa đến tin ấy một cách nhanh nhất).")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'Homepage'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
    if (($action == 'dashboard/ad' || $action == 'dashboard/payment' || $action == 'chat/index' || $action == 'notification/index' || $action == 'dashboard/favorite') && (!isset($cookie['Dashboard']) || empty($cookie['Dashboard']))) {
        $txtArr = [
            Yii::t("tutorial", "<p class='mgB-5'>Cập nhật trạng thái các tin đăng của bạn gồm có:</p><p class='mgB-5'>Lượt tìm kiếm tin đăng,</p><p class='mgB-5'>Lượt yêu thích,</p><p>Lượt chia sẻ.</p>"),
            Yii::t("tutorial", "Trang thông báo sẽ cho bạn xem lịch sử của những người đã xem hoặc thích một tin đăng của bạn, và từ trang này bạn có thể liên lạc họ qua email hoặc tin nhắn."),
            Yii::t("tutorial", 'Trang Trò Chuyện hiển thị "lịch sử trò chuyện" của những người mà bạn đã chat.'),
            Yii::t("tutorial", 'Danh sách các tin đăng mà bạn yêu thích.'),
            Yii::t("tutorial", "Trong trang thanh toán bạn có thể xem được lịch sử giao dịch và nạp thêm chìa khóa vào tài khoản của bạn."),
            Yii::t("tutorial", "Trang thông tin cá nhân của bạn, ở đây bạn có thể thay đổi mật khẩu và thông tin cá nhân của bạn.")
        ];
        if ($action == 'dashboard/ad') {
            $txtArr = [
                Yii::t("tutorial", "<p class='mgB-5'>Cập nhật trạng thái các tin đăng của bạn gồm có:</p><p class='mgB-5'>Lượt tìm kiếm tin đăng,</p><p class='mgB-5'>Lượt yêu thích,</p><p>Lượt chia sẻ.</p>"),
                Yii::t("tutorial", "Trang thông báo sẽ cho bạn xem lịch sử của những người đã xem hoặc thích một tin đăng của bạn, và từ trang này bạn có thể liên lạc họ qua email hoặc tin nhắn."),
                Yii::t("tutorial", 'Trang Trò Chuyện hiển thị "lịch sử trò chuyện" của những người mà bạn đã chat.'),
                Yii::t("tutorial", 'Danh sách các tin đăng mà bạn yêu thích.'),
                Yii::t("tutorial", "Trong trang thanh toán bạn có thể xem được lịch sử giao dịch và nạp thêm chìa khóa vào tài khoản của bạn."),
                Yii::t("tutorial", "Trang thông tin cá nhân của bạn, ở đây bạn có thể thay đổi mật khẩu và thông tin cá nhân của bạn."),
                Yii::t("tutorial", "<p class='mgB-5'>Ở đây bạn có thể theo dõi rõ diễn tiến tin đăng của bạn, dựa trên các số liệu như số lượng các lượt tìm kiếm, yêu thích, chia sẻ hiển thị theo các khoảng thời gian.</p><p class='mgB-5'> Bạn cũng có thể liện hệ với những người đang tìm kiếm sản phẩm của bạn bằng cách nhấp vào tài khoản của khách để gửi tin nhắn hoặc Chat với họ.</p>")
            ];
        }
        $urlPage = Url::to(['site/set-cookie', 'name' => 'Dashboard'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
    if ($action == 'dashboard/statistics' && (!isset($cookie['Statistic']) || empty($cookie['Statistic']))) {
        $txtArr = [
            Yii::t("tutorial", "<p class='mgB-5'>Ở đây bạn có thể theo dõi rõ diễn tiến tin đăng của bạn, dựa trên các số liệu như số lượng các lượt tìm kiếm, yêu thích, chia sẻ hiển thị theo các khoảng thời gian.</p><p class='mgB-5'> Bạn cũng có thể liện hệ với những người đang tìm kiếm sản phẩm của bạn bằng cách nhấp vào tài khoản của khách để gửi tin nhắn hoặc Chat với họ.</p>")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'Statistic'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
    if ($action == 'ad/index1' && (!isset($cookie['BuyListing']) || empty($cookie['BuyListing']))) {
        $txtArr = [
            Yii::t("tutorial", "Để tìm sản phẩm cho bán theo tên thành phố, quận, phường, đường, mã số… bạn hãy gõ vào thanh tìm kiếm ngay hàng đầu và kết quả sẽ tự động thay đổi trên bản đồ và danh sách tiềm năng."),
            Yii::t("tutorial", "Bản đồ lớn sẽ cho phép bạn nhìn thấy địa điểm trong danh sách tiềm năng của bạn, bạn có thể nhấp vào để xem cụ thể các địa điểm."),
            Yii::t("tutorial", "Danh sách tiềm năng bên phải là danh sách sản phẩm được đề xuất có kết quả gần nhất với các yêu cầu tìm kiếm, được phân loại theo cách đánh giá của MetVuong để đảm bảo chất lượng tin cũng như sự liên quan đến yêu cầu của bạn.")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'BuyListing'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
    if ($action == 'ad/index2' && (!isset($cookie['RentListing']) || empty($cookie['RentListing']))) {
        $txtArr = [
            Yii::t("tutorial", "Để tìm sản phẩm cho thuê theo tên thành phố, quận, phường, đường, mã số… bạn hãy gõ vào thanh tìm kiếm ngay hàng đầu và kết quả sẽ tự động thay đổi trên bản đồ và danh sách tiềm năng"),
            Yii::t("tutorial", "Bản đồ lớn sẽ cho phép bạn nhìn thấy địa điểm trong danh sách tiềm năng của bạn, bạn có thể nhấp vào để xem cụ thể các địa điểm."),
            Yii::t("tutorial", "Danh sách tiềm năng bên phải là danh sách sản phẩm được đề xuất có kết quả gần nhất với các yêu cầu tìm kiếm, được phân loại theo cách đánh giá của MetVuong để đảm bảo chất lượng tin cũng như sự liên quan đến yêu cầu của bạn.")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'RentListing'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
    if ($action == 'ad/post' && (!isset($cookie['Post']) || empty($cookie['Post']))) {
        $txtArr = [
            Yii::t("tutorial", "<p class='mgB-5'>Đây là trang để bạn đăng tin sản phẩm cho thuê hoặc bán của bạn.</p><p class='mgB-5'>Metvuong.com khuyến khích bạn đăng các thông tin chi tiết và chính xác, với các tin chất lượng, bạn sẽ đạt số điểm cao và điều đó có nghĩa là sẽ có nhiều khách hàng tiềm năng sẽ liên hệ với bạn trong thời gian ngắn nhất.</p><p class='mgB-5'>Nếu bạn cung cấp thông tin mà sau khi xác minh là chưa chính xác thì chúng tôi sẽ hạ điểm số hoặc loại bỏ tin đăng.</p>")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'Post'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }

    if ($action == 'payment/index' && (!isset($cookie['Payment']) || empty($cookie['Payment']))) {
        $txtArr = [
            Yii::t("tutorial", "Metvuong.com có đầy đủ các hệ thống thanh toán để tối đa hóa sự tiện lợi của bạn, chỉ cần chọn số chìa khóa bạn muốn mua và phương thức thanh toán. Mua sỉ sẽ có chiết khấu tốt hơn.")
        ];
        $urlPage = Url::to(['site/set-cookie', 'name' => 'Payment'], true);
        ?>
        <script>
            $(document).ready(function () {
                textTour(<?=json_encode($txtArr)?>, '<?=$urlPage?>');
            });
        </script>
        <?php
        $this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/tour-intro.js', ['position' => View::POS_END]);
    }
}

