<?php
namespace frontend\controllers;

use vsoft\news\models\CmsShow;
use funson86\cms\models\Status;
use vsoft\news\models\CmsCatalog;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->layout = '@app/views/layouts/layout';
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'page'=>[
                'class'=>'yii\web\ViewAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        $this->layout = '@app/views/layouts/layout';
        \Yii::$app->getSession()->setFlash('reLog', 'Password Changed Successfully.');
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
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
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
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
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLanguage(){
        $language = Yii::$app->request->get('language');
        Yii::$app->language = $language;
        $cookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//            'domain' => '.lancaster.vn' // <<<=== HERE
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAboutUs(){
        $ids = CmsCatalog::getArraySubCatalogId(0, CmsCatalog::find()->where([
            'surname' => 'ABOUT',
        ])->asArray()->all());
        $query = CmsShow::find();
        $query->where([
            'status' => Status::STATUS_ACTIVE,
            'catalog_id' => $ids,
        ]);

        $pagination = new Pagination([
            'defaultPageSize' => isset(\Yii::$app->params['cmsListPageCount']) ? Yii::$app->params['cmsListPageCount'] : 20,
            'totalCount' => $query->count(),
        ]);

        $news = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('about/index', ['news'=>$news]);
    }

    public function actionNews(){
        $ids = CmsCatalog::getArraySubCatalogId(0, CmsCatalog::find()->where([
            'surname' => 'NEWS',
        ])->asArray()->all());
        $query = CmsShow::find();
        $query->where([
            'status' => Status::STATUS_ACTIVE,
            'catalog_id' => $ids,
        ]);

        $pagination = new Pagination([
            'defaultPageSize' => isset(\Yii::$app->params['cmsListPageCount']) ? Yii::$app->params['cmsListPageCount'] : 30,
            'totalCount' => $query->count(),
        ]);

        $news = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('news/index', ['news'=>$news, 'pagination' => $pagination]);
    }

    public function actionNewsDetail()
    {
        $id = \Yii::$app->request->get('id');
        if (!$id) $this->goHome();

        $detail = CmsShow::findOne($id);
        /**
         * Related post
         */
        $surname = $detail->catalog->surname;
        $catids = ArrayHelper::map(CmsCatalog::find()->where([
            'surname' => $surname,
        ])->asArray()->all(),
            'id', 'id');

        $relatedPost = CmsShow::find()->where([
            'status' => Status::STATUS_ACTIVE,
            'catalog_id' => $catids,
        ])->andWhere(['<>', 'id', $id])->all();

        return $this->render('news/detail', ['detail'=>$detail, 'relatedPost'=>$relatedPost]);
    }
}
