<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Reservation;

class ReservationController extends Controller
{
    public function actionView($id)
    {
        if (!Yii::$app->session->has('internaute')) {
            return $this->redirect(['site/login']);
        }

        $reservation = $this->findModelForUser((int)$id);

        return $this->render('view', [
            'reservation' => $reservation,
        ]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->session->has('internaute')) {
            return $this->redirect(['site/login']);
        }

        if (!Yii::$app->request->isPost) {
            return $this->redirect(['site/profil']);
        }

        $reservation = $this->findModelForUser((int)$id);
        $reservation->delete();

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'success',
                'type' => 'success',
                'message' => 'Reservation supprimee.',
                'redirect' => \yii\helpers\Url::to(['site/profil']),
            ]);
        }

        Yii::$app->session->setFlash('success', 'Reservation supprimee.');
        return $this->redirect(['site/profil']);
    }

    private function findModelForUser(int $id): Reservation
    {
        $sessionUser = Yii::$app->session->get('internaute');
        $userId = (int)$sessionUser['id'];

        $reservation = Reservation::find()
            ->where(['id' => $id, 'voyageur' => $userId])
            ->with(['voyageInfo.trajetInfo', 'voyageInfo.conducteurInfo'])
            ->one();

        if (!$reservation) {
            throw new NotFoundHttpException('Reservation introuvable.');
        }

        return $reservation;
    }
}
