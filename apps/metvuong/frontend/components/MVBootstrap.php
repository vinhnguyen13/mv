<?php
namespace frontend\components;
use Yii;
use yii\base\BootstrapInterface;
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

        if (empty($preferredLanguage)) {
            $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
        }

        $app->language = $preferredLanguage;
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