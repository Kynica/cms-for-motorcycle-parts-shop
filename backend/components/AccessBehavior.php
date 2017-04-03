<?php
namespace backend\components;

use Yii;
use yii\base\Behavior;
use yii\console\Controller;
use yii\helpers\Url;
/**
 * Redirects all users to defined page if they are not logged in
 *
 * Class AccessBehavior
 * @package app\components
 * @author  Artem Voitko <r3verser@gmail.com>
 */
class AccessBehavior extends Behavior
{
    /**
     * @var string Yii route format string
     */
    public $redirectUri;
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->redirectUri)) {
            $this->redirectUri = Yii::$app->getUser()->loginUrl;
        }
    }
    /**
     * Subscribe for event
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }
    /**
     * On event callback
     */
    public function beforeAction()
    {
        if (Yii::$app->getUser()->isGuest && Yii::$app->getRequest()->url !== Url::to($this->redirectUri)) {
            Yii::$app->getResponse()->redirect($this->redirectUri)->send();
            exit;
        } /*else {
            if (!Yii::$app->user->can('backend') && Yii::$app->getRequest()->url !== '/site/empty-page') {
                Yii::$app->getResponse()->redirect('/site/empty-page');
            }
        } */
    }
}