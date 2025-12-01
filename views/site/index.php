 <?php
use yii\helpers\Html;

$this->title = 'Accueil CERICar';
?>

<div class="site-index">

    <div class="jumbotron text-center" style="background-color: #fff; padding-bottom: 20px;">
        <h1 style="margin-top: 0; color: #333;">Bienvenue sur CERICar</h1>
        <p class="lead">Projet Web - Étape 2</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-md-12 text-center">
                <h3 style="color: #0056b3; margin-bottom: 1'0px; font-weight: bold;">
                    🧪 Liste des profils à tester
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                
                <?php 
                // On récupère la liste ici, car nous sommes sur la page d'accueil
                $internautes = \app\models\Internaute::find()->all(); 
                ?>

                <?php if (empty($internautes)): ?>
                    <div class="alert alert-warning text-center">Aucun utilisateur trouvé.</div>
                <?php else: ?>
                    
                    <div class="table-responsive" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <table class="table table-bordered table-striped" style="border: 2px solid #999; margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #337ab7; color: white;">
                                    <th style="text-align:center; border: 1px solid #333;">Photo</th>
                                    <th style="text-align:center; border: 1px solid #333;">Pseudo</th>
                                    <th style="text-align:center; border: 1px solid #333;">Nom Complet</th>
                                    <th style="text-align:center; border: 1px solid #333;">Statut</th>
                                    <th style="text-align:center; border: 1px solid #333;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($internautes as $user): ?>
                                    <tr>
                                        <td style="text-align:center; vertical-align: middle; border: 1px solid #ccc;">
                                            <?php if ($user->photo): ?>
                                                <img src="<?= Html::encode($user->photo) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border: 1px solid #999;">
                                            <?php else: ?>
                                                👤
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle; border: 1px solid #ccc; font-weight:bold; color:#0056b3;">
                                            <?= Html::encode($user->pseudo) ?>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle; border: 1px solid #ccc;">
                                            <?= Html::encode($user->prenom) ?> <?= Html::encode($user->nom) ?>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle; border: 1px solid #ccc;">
                                            <?= $user->permis ? '<span class="label label-success">Conducteur</span>' : '<span class="label label-default">Passager</span>' ?>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle; border: 1px solid #ccc;">
                                            <a href="index.php?r=internaute/test&pseudo=<?= urlencode($user->pseudo) ?>" style="font-weight: bold; text-decoration: underline;">
                                                Tester
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>