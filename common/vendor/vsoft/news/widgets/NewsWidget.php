<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 8:49 AM
 */

namespace vsoft\news\widgets;


use funson86\cms\models\CmsShow;
use Yii;
use yii\base\Widget;

class NewsWidget extends Widget
{
    public $c_id, $s_id, $view;

    public function run()
    {
        if ($this->c_id > 0) {
            if ($this->s_id > 0) {
                // render to detail page
                $detail = CmsShow::findOne($this->s_id);
                Yii::$app->response->redirect(['/news/detail', 'detail' => $detail]);
            } else {
                // show post list with catalog_id
                if ($this->view === 'list') {
                    $list = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])->all();
                    Yii::$app->response->redirect(['/news/list', 'list' => $list]);
                } else {
                    // show template by catalog id

                    if ($this->c_id == 4) {
                        $show = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])
                            ->orderBy([
                                'id' => SORT_DESC,
                            ])->limit(4)->all();
                        return $this->render('template_2_3', [
                            'template_2_3' => $show
                        ]);
                    }
                    else if ($this->c_id == 5) {
                        $show = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])
                            ->orderBy([
                                'id' => SORT_DESC,
                            ])->limit(2)->all();

                        return $this->render('template_1_2', [
                            'template_1_2' => $show
                        ]);
                    }
                    else if ($this->c_id == 6) {
                        $show = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])
                            ->orderBy([
                                'id' => SORT_DESC,
                            ])->limit(1)->all();
                        return $this->render('template_1_1', [
                            'template_1_1' => $show
                        ]);
                    }
                    else if ($this->c_id == 7) {
                        $show = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])
                            ->orderBy([
                                'id' => SORT_DESC,
                            ])->limit(8)->all();
                        return $this->render('template_1_8', [
                            'template_1_8' => $show
                        ]);
                    }
                    else if ($this->c_id == 8) {
                        $show = CmsShow::find()->where('catalog_id = :catalog_id', [':catalog_id' => $this->c_id])->limit(3)->all();
                        return $this->render('template_3_1', [
                            'template_3_1' => $show
                        ]);
                    }
                }
            }
        } else return $this->redirect('/');
    }
}