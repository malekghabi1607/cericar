<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Internaute;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }

        return $this->redirect(['site/index']);
    }

    /**
     * CONNEXION
     */
    public function actionLogin()
    {
        if (Yii::$app->session->has('internaute')) {
            return $this->goHome();
        }

        $request = Yii::$app->request;

        if ($request->isPost) {
            $pseudo = $request->post('pseudo');
            $password = $request->post('password');

            $user = Internaute::findOne(['pseudo' => $pseudo]);

            if ($user && $user->validatePassword($password)) {
                // On enregistre les infos clés en session
                Yii::$app->session->set('internaute', [
                    'id' => $user->id,
                    'pseudo' => $user->pseudo,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                ]);
                Yii::$app->session->set('id_internaute', $user->id);
                return $this->redirect(['site/login-success']);
            }

            Yii::$app->session->setFlash('error', 'Identifiant ou mot de passe incorrect');
        }

        return $this->render('login');
    }

    public function actionLoginSuccess()
    {
        if (!Yii::$app->session->has('internaute')) {
            return $this->redirect(['site/login']);
        }
        return $this->render('login-success');
    }

    /**
     * DÉCONNEXION (C'est ici que ça bloquait)
     */
    public function actionLogout()
    {
        // 1. On vide la session personnalisée
        Yii::$app->session->remove('internaute');
        
        // 2. On détruit carrément la session pour être sûr
        Yii::$app->session->destroy();

        // 3. On déconnecte le composant Yii au cas où
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new Internaute();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post())) {
            $model->photoFile = \yii\web\UploadedFile::getInstance($model, 'photoFile');
            if ($model->validate()) {
                if ($model->photoFile) {
                    $uploadDir = Yii::getAlias('@runtime/avatars');
                    try {
                        \yii\helpers\FileHelper::createDirectory($uploadDir);
                    } catch (\Throwable $e) {
                        $model->addError('photoFile', "Impossible de creer le dossier d'upload.");
                    }
                    $fileName = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $model->photoFile->extension;
                    $filePath = $uploadDir . '/' . $fileName;
                    if (!$model->hasErrors() && $model->photoFile->saveAs($filePath)) {
                        $model->photo = $fileName;
                    }
                }

                if (!$model->hasErrors()) {
                    $model->photoFile = null;
                    if ($model->save(false)) {
                        if (Yii::$app->request->isAjax) {
                            return $this->asJson([
                                'status' => 'success',
                                'type' => 'success',
                                'message' => 'Inscription reussie.',
                                'redirect' => \yii\helpers\Url::to(['site/register-success']),
                            ]);
                        }
                        return $this->redirect(['site/register-success']);
                    }
                }
            }
            if (Yii::$app->request->isAjax) {
                $firstError = implode(' ', $model->getFirstErrors());
                return $this->asJson([
                    'status' => 'error',
                    'type' => 'error',
                    'message' => $firstError ?: 'Erreur lors de l\'inscription.',
                ]);
            }
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionRegisterSuccess()
    {
        return $this->render('register-success');
    }

    public function actionAvatar($id)
    {
        $user = Internaute::findOne((int)$id);
        if (!$user || empty($user->photo)) {
            throw new \yii\web\NotFoundHttpException('Image introuvable.');
        }

        $fileName = basename($user->photo);
        $filePath = Yii::getAlias('@runtime/avatars/' . $fileName);
        if (!is_file($filePath)) {
            throw new \yii\web\NotFoundHttpException('Image introuvable.');
        }

        return Yii::$app->response->sendFile($filePath, null, ['inline' => true]);
    }

    public function actionProfilEdit()
    {
        if (!Yii::$app->session->has('internaute')) {
            return $this->redirect(['site/login']);
        }

        $sessionUser = Yii::$app->session->get('internaute');
        $model = Internaute::findOne((int)$sessionUser['id']);

        if (!$model) {
            return $this->redirect(['site/login']);
        }

        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
            $model->photoFile = \yii\web\UploadedFile::getInstance($model, 'photoFile');
            if ($model->validate()) {
                if ($model->photoFile) {
                    $uploadDir = Yii::getAlias('@runtime/avatars');
                    try {
                        \yii\helpers\FileHelper::createDirectory($uploadDir);
                    } catch (\Throwable $e) {
                        $model->addError('photoFile', "Impossible de creer le dossier d'upload.");
                    }
                    $fileName = 'avatar_' . $model->id . '_' . time() . '.' . $model->photoFile->extension;
                    $filePath = $uploadDir . '/' . $fileName;
                    if (!$model->hasErrors() && $model->photoFile->saveAs($filePath)) {
                        $model->photo = $fileName;
                    }
                }

                if (!$model->hasErrors()) {
                    $model->photoFile = null;
                    if ($model->save(false)) {
                        Yii::$app->session->set('internaute', [
                            'id' => $model->id,
                            'pseudo' => $model->pseudo,
                            'nom' => $model->nom,
                            'prenom' => $model->prenom,
                        ]);
                        if (Yii::$app->request->isAjax) {
                            return $this->asJson([
                                'status' => 'success',
                                'type' => 'success',
                                'message' => 'Profil mis a jour.',
                            ]);
                        }
                        return $this->redirect(['site/profil']);
                    }
                }
            }
            if (Yii::$app->request->isAjax) {
                $firstError = implode(' ', $model->getFirstErrors());
                return $this->asJson([
                    'status' => 'error',
                    'type' => 'error',
                    'message' => $firstError ?: 'Erreur lors de la mise a jour.',
                ]);
            }
        }

        return $this->render('profil-edit', ['model' => $model]);
    }








public function actionProfil()
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    $sessionUser = Yii::$app->session->get('internaute');
    $user = Internaute::findOne((int)$sessionUser['id']);

    if (!$user) {
        Yii::$app->session->remove('internaute');
        return $this->redirect(['site/login']);
    }

    $reservationsRaw = \app\models\Reservation::find()
        ->where(['voyageur' => $user->id])
        ->with(['voyageInfo.trajetInfo', 'voyageInfo.conducteurInfo'])
        ->all();

    $reservationsGrouped = [];
    foreach ($reservationsRaw as $res) {
        $voyage = $res->voyageInfo;
        $trajet = $voyage ? $voyage->trajetInfo : null;
        if (!$voyage) {
            continue;
        }

        $key = (int)$voyage->id;
        if (!isset($reservationsGrouped[$key])) {
            $reservationsGrouped[$key] = [
                'trajet' => $trajet,
                'voyage' => $voyage,
                'conducteur' => $voyage ? $voyage->conducteurInfo : null,
                'nb_reservations' => 0,
                'places_reservees' => 0,
                'reservation_ids' => [],
            ];
        }

        $reservationsGrouped[$key]['nb_reservations'] += 1;
        $reservationsGrouped[$key]['places_reservees'] += (int)$res->nbplaceresa;
        $reservationsGrouped[$key]['reservation_ids'][] = (int)$res->id;
    }

    $mesPropositions = \app\models\Voyage::find()
        ->where(['conducteur' => $user->id])
        ->with(['trajetInfo'])
        ->all();

    $viewData = [
        'user' => $user,
        'reservations' => $reservationsGrouped,
        'totalReservations' => count($reservationsRaw),
        'mesPropositions' => $mesPropositions,
    ];

    if (Yii::$app->request->isAjax && Yii::$app->request->get('partial')) {
        return $this->renderPartial('profil', $viewData);
    }

    return $this->render('profil', $viewData);
}

public function actionSavePermis()
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    $request = Yii::$app->request;
    if (!$request->isPost) {
        return $this->redirect(['site/profil']);
    }

    $permis = trim((string)$request->post('permis', ''));
    if ($permis === '') {
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'error',
                'type' => 'error',
                'message' => 'Veuillez saisir un numero de permis.',
            ]);
        }
        return $this->redirect(['site/profil', '#' => 'permit-section']);
    }

    $sessionUser = Yii::$app->session->get('internaute');
    $user = Internaute::findOne((int)$sessionUser['id']);
    if (!$user) {
        Yii::$app->session->remove('internaute');
        return $this->redirect(['site/login']);
    }

    $user->scenario = 'update';
    $user->permis = $permis;
    if ($user->save()) {
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'success',
                'type' => 'success',
                'message' => 'Permis enregistre.',
                'redirect' => \yii\helpers\Url::to(['site/profil', '#' => 'permit-section']),
            ]);
        }
        return $this->redirect(['site/profil', '#' => 'permit-section']);
    }

    if (Yii::$app->request->isAjax) {
        $firstError = implode(' ', $user->getFirstErrors());
        return $this->asJson([
            'status' => 'error',
            'type' => 'error',
            'message' => $firstError ?: 'Erreur lors de l\'enregistrement du permis.',
        ]);
    }

    return $this->redirect(['site/profil', '#' => 'permit-section']);
}


}
