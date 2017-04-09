<?php

namespace backend\controllers;

use Yii;
use common\models\Profile;
use backend\models\ProfileSearch;
use backend\models\UserForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Profile model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model              = new Profile();
        $userForm           = new UserForm();
        $userForm->scenario = UserForm::SCENARIO_CREATE;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $userForm->load(Yii::$app->request->post());
            $user = $userForm->create(); //TODO Rewrite - Profile must create user, not user form.
            if (! empty($user)) {
                $model->user_id = $user->id;
                if ($model->save())
                    return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model'    => $model,
            'userForm' => $userForm
        ]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model    = $this->findModel($id);
        $userForm = new UserForm([
            'username' => $model->user->username,
            'email' => $model->user->email,
        ]);
        $userForm->scenario = UserForm::SCENARIO_UPDATE;

        $model->load(Yii::$app->request->post());
        $model->save();

        $userForm->load(Yii::$app->request->post());
        $userForm->update($model->user);

        return $this->render('update', [
            'model'    => $model,
            'userForm' => $userForm,
        ]);
    }

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
