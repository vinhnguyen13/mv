<?php
namespace frontend\components;
use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MVBootstrap implements BootstrapInterface
{
    public $supportedLanguages = [];
    public $cookieName = 'language';
    public $expireDays = 30;
    public $cookieDomain = '';
    public $callback;

    public function bootstrap($app)
    {
        $preferredLanguage = isset($app->request->cookies['language']) ? (string)$app->request->cookies['language'] : null;
        // or in case of database:
        // $preferredLanguage = $app->user->language;
        /*if (empty($preferredLanguage)) {
            $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
            $app->language = $preferredLanguage;
        }*/
        if (!empty($preferredLanguage)) {
            $app->language = $preferredLanguage;
        }

        $this->changeLanguage();

        if(Yii::$app->mobileDetect->isMobile() && false){
            Yii::$app->set('view', [
                'class' => 'yii\web\View',
//                'title' => '2amigOS! Consulting Group LLC',
                'theme' => [
                    'basePath' => '@webroot/themes/mv_mobile1',
                    'baseUrl' => '/frontend/web/themes/mv_mobile1',
                    'pathMap' => [
                        '@app/views' => '@webroot/themes/mv_mobile1/views',
                        '@dektrium/user/views' => '@webroot/themes/mv_mobile1/views',
                    ],
                ],
            ]);
        }

        if(true){
            $langActive = $app->language;
            if($app->language == $this->supportedLanguages[0]){
                $rules1 = $this->getTopRules($this->supportedLanguages[0]);
                $rules2 = $this->getTopRules($this->supportedLanguages[1]);
                $rules3 = $this->getBottomRules($this->supportedLanguages[0]);
                $rules4 = $this->getBottomRules($this->supportedLanguages[1]);
            }elseif($app->language == $this->supportedLanguages[1]){
                $rules1 = $this->getTopRules($this->supportedLanguages[1]);
                $rules2 = $this->getTopRules($this->supportedLanguages[0]);
                $rules3 = $this->getBottomRules($this->supportedLanguages[1]);
                $rules4 = $this->getBottomRules($this->supportedLanguages[0]);
            }
            $rules_1 = ArrayHelper::merge($rules1, $rules2);
            $rules_2 = ArrayHelper::merge($rules3, $rules4);
            $apiRules = ['class' => 'yii\rest\UrlRule', 'controller' => 'map'];
            
            Yii::$app->set('urlManager', [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => ArrayHelper::merge($rules_1, $rules_2, $apiRules)
            ]);
        }

    }

    private function getTopRules($language)
    {
        return [
            '/' => 'site/index',
            Yii::t('url', 'page', [], $language).'/<view>' => 'site/page',

            Yii::t('url', 'tin-tuc', [], $language) => 'news/index',
            Yii::t('url', 'tin-tuc', [], $language).'/<cat_id:\d+>-<cat_slug>' => 'news/list',
            Yii::t('url', 'tin-tuc', [], $language).'/chi-tiet/<id:\d+>-<slug>' => 'news/view',

            Yii::t('url', 'du-an', [], $language) => 'building-project/index',
            Yii::t('url', 'du-an', [], $language).'/<slug>' => 'building-project/view',

            Yii::t('url', 'nha-dat-ban', [], $language) => 'ad/index1',
            Yii::t('url', 'nha-dat-cho-thue', [], $language) => 'ad/index2',
            Yii::t('url', 'dang-tin', [], $language) => 'ad/post',
            Yii::t('url', 'bat-dong-san', [], $language).'/redirect' => 'ad/redirect',
            Yii::t('url', 'bat-dong-san', [], $language).'/post-listing' => 'ad/post-listing',
            Yii::t('url', 'nha-dat-ban', [], $language).'/<id:\d+>-<slug>' => 'ad/detail1',
        	Yii::t('url', 'nha-dat-cho-thue', [], $language).'/<id:\d+>-<slug>' => 'ad/detail2',
            Yii::t('url', 'bat-dong-san', [], $language).'/update/<id:\d+>' => 'ad/update',
            Yii::t('url', 'thanh-vien', [], $language).'/<usrn>/avatar' => 'member/avatar',
            Yii::t('url', 'tro-chuyen', [], $language).'/with/<username>' => 'chat/with',
            Yii::t('url', 'goi-gia', [], $language).'' => 'payment/package',

            'mvuser/protect/<action>' => 'user/security/<action>',
            'mvuser/join/<action>' => 'user/registration/<action>',
            'mvuser/forgot/<action>' => 'user/recovery/<action>',
			'listing/<action>' => 'ad/<action>',
            '<usrn>/avatar' => 'member/avatar',
        ];
    }

    private function getBottomRules($language){
        return [
            '<username>' => 'member/profile',
            '<username>/'.Yii::t('url', 'cai-dat', [], $language) => 'member/update-profile',
            '<username>/'.Yii::t('url', 'thong-bao', [], $language) => 'notification/index',
            '<username>/'.Yii::t('url', 'thong-bao', [], $language).'/'.Yii::t('url', 'cap-nhat', [], $language) => 'notification/update',
            '<username>/'.Yii::t('url', 'danh-sach-tin-dang', [], $language) => 'dashboard/ad',
            '<username>/'.Yii::t('url', 'payment', [], $language) => 'dashboard/payment',
            '<username>/'.Yii::t('url', 'tro-chuyen', [], $language) => 'chat/index',

        ];
    }

    public function changeLanguage()
    {
        if (isset($_GET['language-change'])) {
            if ($this->_isValidLanguage($_GET['language-change'])) {
                return $this->saveLanguage($_GET['language-change']);
            } else if (!Yii::$app->request->isAjax) {
                return $this->_redirect();
            }
        }
    }

    public function saveLanguageIntoCookie($language)
    {
        $cookie = new \yii\web\Cookie([
            'name' => $this->cookieName,
            'domain' => $this->cookieDomain,
            'value' => $language,
            'httpOnly' => false,
            'expire' => time() + 86400 * $this->expireDays
        ]);
        Yii::$app->response->cookies->add($cookie);
    }

    private function _redirect()
    {
        $language = Yii::$app->language;
        $redirect = Yii::$app->request->absoluteUrl == Yii::$app->request->referrer ? '/' : Yii::$app->request->referrer;
        $parseRequest = Yii::$app->getUrlManager()->parseRequest(Yii::$app->request);
        $params = ['/'];
        if(!empty($parseRequest[1]) && is_array($parseRequest[1])){
            $params = array_merge(['/'.$parseRequest[0]], $parseRequest[1], Yii::$app->request->getQueryParams());
        }elseif(!empty($parseRequest[0]) && empty($parseRequest[1])){
            $params = array_merge(['/'.$parseRequest[0]], Yii::$app->request->getQueryParams());
        }
        if(!empty($params['language-change'])){
//            $params['language'] = $params['language-change'];
            unset($params['language-change']);
        }
        $url = Url::to($params);
        return Yii::$app->response->redirect($url);
    }

    public function saveLanguage($language)
    {
        Yii::$app->language = $language;
        $this->saveLanguageIntoCookie($language);

        if (is_callable($this->callback)) {
            call_user_func($this->callback);
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->end();
        }

        return $this->_redirect();
    }

    private function _isValidLanguage($language)
    {
        return is_string($language) && (isset($this->supportedLanguages[$language]) || in_array($language, $this->supportedLanguages));
    }
}