<?php
namespace frontend\controllers;

use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use frontend\models\AdProductSearch;
use frontend\models\LoginForm;
use frontend\models\Profile;
use frontend\models\RegistrationForm;
use frontend\models\ResetPasswordForm;
use frontend\models\Token;
use frontend\models\User;
use frontend\models\UserLocation;
use frontend\models\UserReview;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\mongodb\Session;
use yii\web\BadRequestHttpException;
use frontend\components\Controller;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\RecoveryForm;
use frontend\models\ProfileForm;

/**
 * Site controller
 */
class MemberController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public $_module;
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function init(){
        $this->_module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model = Yii::createObject(LoginForm::className());
                if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
                    return ['statusCode' => 200, 'parameters' => ['username' => !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
                } else {
                    return ['statusCode' => 404, 'parameters' => $model->errors];
                }
            }else{
                return $this->renderAjax('login');
            }
        }
        return $this->render('login');
        throw new NotFoundHttpException('Not Found');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model = Yii::createObject(RegistrationForm::className());
                $model->load(Yii::$app->request->post());
                $model->validate();
                if (!$model->hasErrors()) {
                    $user = $model->register();
                    if (!empty($user) && Yii::$app->getUser()->login($user, $this->_module->rememberFor)) {
                        return ['statusCode' => 200, 'parameters' => ['username' => !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
                    }
                } else {
                    return ['statusCode' => 404, 'parameters' => $model->errors];
                }
            }else{
                return $this->renderAjax('signup');
            }
        }
        return $this->render('signup');
        throw new NotFoundHttpException('Not Found');
    }
    /**
     * Used to redirect profile page not login
     * @param $id mix
     * @param $code random string
     **/
    public function actionConfirmLogin($id, $code)
    {
        $token = Token::find()->where(['MD5(CONCAT(user_id, code))' => $id, 'code' => $code, 'type' => Token::TYPE_CRAWL_USER_EMAIL])->one();
        $countToken = count($token);
        if($countToken > 0) {
            $user = $token->user;
            $loginStatus = Yii::$app->getUser()->login($user, 0);
            if ($loginStatus) {
                $token->updateAttributes([
                    'code' => Yii::$app->security->generateRandomString(),
                ]);
                $user_id = $user->id;
                $email = $user->email;
                $contacts = AdContactInfo::getDb()->cache(function() use($email){
                    $sql = "SELECT group_concat(product_id) as list_id FROM ad_contact_info where email = '{$email}'";
                    return AdContactInfo::getDb()->createCommand($sql)->queryAll();
                });

                if(count($contacts) && isset($contacts[0]["list_id"])) {
                    $list_id = $contacts[0]["list_id"];
                    $update_sql = "UPDATE `ad_product` SET `user_id`={$user_id} WHERE id IN ({$list_id})";
                    AdProduct::getDb()->createCommand($update_sql)->execute();
                }
            }
            $this->redirect(Url::to(['member/profile', 'username' => $user->username], true));
        } else {
            $this->redirect(Url::home(true));
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgot()
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'request',
        ]);
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg =$model->sendRecoveryMessage()) !== false){
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($id, $code)
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = Token::find()->where(['MD5(CONCAT(user_id, code))' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/_systems/_alert', [
                'title'  => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'reset',
        ]);

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg = $model->resetPassword($token)) !== false){
                    if(count($token) > 0) {
                        $user = $token->user;
                        Yii::$app->getUser()->login($user, 0);
                    }
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }
        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    public function actionAvatar($usrn)
    {
        $user = User::findOne(['username'=>$usrn]);
        $profile = $user->profile;
        $avatarPath = Yii::getAlias('@webroot').( '/images/default-avatar.jpg');
        if($profile->avatar) {
            $pathinfo = pathinfo($profile->avatar);
            $filePath = Yii::getAlias('@store').'/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension'];
            if(file_exists($filePath)){
                $avatarPath = $filePath;
            }
        }
        $pathinfo = pathinfo($avatarPath);
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/'.$pathinfo['extension']);
        $response->format = Response::FORMAT_RAW;
        if ( !is_resource($response->stream = fopen($avatarPath, 'r')) ) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }
        return $response->send();
    }

    public function actionProfile($username)
    {
//        $this->checkAccess();
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $sale_products = array();
        $rent_products = array();
        $model = $model->loadProfile($username, 'updateprofile');
        if($model) {
            $AdProductSearch = new AdProductSearch();
            $query = $AdProductSearch->search(\Yii::$app->request->get());
            $query->addSelect('ad_product.created_at, ad_product.category_id, ad_product.type, ad_images.file_name, ad_images.folder');
            $query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id')->groupBy('ad_product.id');
            $query->where('ad_product.user_id = :uid', [':uid' => $model->user_id]);
            $detectProducts = $query->orderBy(['district_id' => SORT_ASC, 'city_id'=> SORT_ASC, 'id' => SORT_DESC])->all();
            if(count($detectProducts) > 0){
                foreach($detectProducts as $product){
                    if($product->type == 1) {
                        array_push($sale_products, $product);
                    }
                    else {
                        array_push($rent_products, $product);
                    }
                }
            }
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);
            $pagination->defaultPageSize = 6;
            $products = $query->offset($pagination->offset)->limit($pagination->limit)
                ->orderBy(['district_id' => SORT_ASC, 'city_id'=> SORT_ASC, 'id' => SORT_DESC])->all();
        }
        return $this->render('user/profile', [
            'model' => $model, 'username'=>$username,
            'products' => $products, 'pagination' => $pagination,
            'sale_products' => $sale_products, 'rent_products' => $rent_products
        ]);
    }

    public function actionProfileRenderEmail($username, $pid = null){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $user = User::find()->where('username = :usrn', [':usrn' => $username])->one();
            if($user) {
                $profile = $user->profile;
                $recipientEmail = empty($profile->public_email) ? $user->email : $profile->public_email;
                $product = null;
                if(!empty($pid)){
                    $product = AdProduct::getDb()->cache(function() use($pid){
                        return AdProduct::find()->where('id = :pid', [':pid' => $pid])->one();
                    });
                    $categories = \vsoft\ad\models\AdCategory::getDb()->cache(function(){
                        return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
                    });
                    $types = \vsoft\ad\models\AdProduct::getAdTypes();
                    $product_type = $types[$product->type];

                    $address = $product->getAddress();
                    $category = ucfirst(Yii::t('ad', $categories[$product->category_id]['name'], null, Yii::$app->language)). " " .mb_strtolower(Yii::t('ad', $product_type, null, Yii::$app->language));
                    $area = $product->area;
                    $room_no = $product->adProductAdditionInfo->room_no;
                    $toilet_no = $product->adProductAdditionInfo->toilet_no;
                    $price = StringHelper::formatCurrency($product->price);
                    $imageUrl = $product->representImage;
                    if (!filter_var($imageUrl, FILTER_VALIDATE_URL))
                        $imageUrl = Yii::$app->urlManager->hostInfo . $product->representImage;
                    $detailUrl = $product->urlDetail(true);
                    $description = \yii\helpers\StringHelper::truncate($product->content, 150);
                }
                return [
                    'email' => $recipientEmail,
                    'ava' => $profile->getAvatarUrl(),
                    'name' => empty($profile->name) ? $recipientEmail : $profile->name,
                    'address' => empty($user->location) ? "" : $user->location->city,
                    'product' => [
                        'pid' => $pid,
                        'address' => $address,
                        'domain' => Yii::$app->urlManager->getHostInfo(),
                        'category' => $category,
                        'area' => $area,
                        'room_no' => $room_no,
                        'toilet_no' => $toilet_no,
                        'price' => $price,
                        'imageUrl' => $imageUrl,
                        'detailUrl' => $detailUrl,
                        'description' => $description
                    ]
                ];
            }
        }
        return null;
    }

    public function actionUpdateProfile($username)
    {
        $this->view->params['menuUpdateProfile'] = true;
        $this->view->params['isDashboard'] = true;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        if($username == Yii::$app->user->identity->getUsername()) {
            $model = Yii::createObject([
                'class'    => ProfileForm::className(),
                'scenario' => 'updateprofile',
            ]);
            $model = $model->loadProfile($username, 'updateprofile');
            if(Yii::$app->request->isAjax){
                if(Yii::$app->request->isPost) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $post = Yii::$app->request->post();
                    if ($post) {
                        $scenario = trim($post["scenario"]);
                        $model = Yii::createObject([
                            'class' => ProfileForm::className(),
                            'scenario' => $scenario,
                        ]);
                        $model = $model->loadProfile($username, $scenario);
                        $model->load($post);
                        $model->validate();
                        if (!$model->hasErrors()) {
                            $model->updateProfile();
                            return ['statusCode' => 200, 'model' => $model];
                        } else {
                            return ['statusCode' => 400, 'parameters' => $model->errors, 'user' => 'error'];
                        }
                    }
                }
                return $this->renderAjax('user/updateProfile',['model'=> $model]);
            }
            return $this->render('user/updateProfile',['model'=> $model]);
        }

        return $this->redirect(Url::to(['member/login']));
    }

    public function actionUpdateUserLocation()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        $model = UserLocation::find()->where(['user_id' => Yii::$app->user->id])->one();
        if(empty($model))
            $model = new UserLocation();

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if($post) {
                $model->user_id = Yii::$app->user->id;
                $model->city_id = isset($post["UserLocation"]["city_id"]) ? (int)$post["UserLocation"]["city_id"] : null;
                $model->validate();
                if (!$model->hasErrors() ) {
                    $model->save(false);
                    return ['statusCode' => 200, 'model' => $model];
                } else {
                    return ['statusCode' => 400, 'parameters' => $model->errors];
                }
            }
        }
        return ['statusCode' => 400];
    }

    public function actionPassword()
    {
        $this->checkAccess();
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'password',
        ]);

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $model->resetPass();
                    return ['statusCode'=>200];
                }else{
                    return ['statusCode'=> 400, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/password', [
                'model' => $model
            ]);
        }

        return $this->render('user/password', [
            'model'=>$model
        ]);
    }

    public function actionReview($review_id, $name, $username){
        $this->checkAccess();
        $model = UserReview::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['review_id' => $review_id])->one();
        if(empty($model))
            $model = new UserReview();
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(count($post) > 0) {
                $model->user_id = Yii::$app->user->id;
                $model->review_id = $review_id;
                $model->name = $name;
                $model->username = $username;
                $model->rating = isset($post["rating"]) ? (int)$post["rating"] : 0;
                $model->type = isset($post["type"]) ? (int)$post["type"] : 1;
                $model->description = isset($post["review_content"]) ? $post["review_content"] : null;
                $model->created_at = time();
                $model->ip = isset($post["ip"]) ? $post["ip"] : null;
                $model->is_report = isset($post["is_report"]) ? $post["is_report"] : null;
                $model->validate();
                if (!$model->hasErrors() && $model->save()) {
                    // update rating point in profile
                    $reviews = UserReview::find()->where(['review_id' => $review_id])->orderBy(['created_at' => SORT_DESC])->all();
                    $countReview = count($reviews);
                    if($countReview > 0){
                        $sumRating = 0;
                        foreach($reviews as $review){
                            $sumRating = $sumRating + $review->rating;
                        }
                        $avgRating = $sumRating / $countReview;

                        $user = User::findIdentity($review_id);
                        $profile = $user->profile;
                        $profile->rating_point = $avgRating;
                        $profile->rating_no = $countReview;
                        $profile->save(false);

                        return ['statusCode' => 200, 'review' => $model, 'profile' => $profile];
                    }
                    return ['statusCode' => 200, 'send_status' => true];
                } else {
                    return ['statusCode' => 400, 'parameters' => $model->errors];
                }
            }
        }
        return ['statusCode' => 404];
    }

    public function actionReviewAll(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $last_time = (int)Yii::$app->request->get('last_time');
            $review_id = (int)Yii::$app->request->get('review_id');
            $reviews = \frontend\models\UserReview::find()->where('review_id = :rid', [':rid' => $review_id])
                ->andWhere('created_at < :at', [':at' => $last_time])
                ->orderBy(['created_at' => SORT_DESC])->all();
            if(count($reviews) > 0){
                return $this->renderAjax('/member/_partials/review', ['reviews' => $reviews]);
            }
        }
        return false;
    }

}
