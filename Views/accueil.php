
<?php
ob_start();
$title = "SKI API ACCUEIL";
?>

<h1 class="title is-size-1 has-text-danger">Compétition de ski 2021</h1>
<h2 class="title is-size-2 has-text-info">CREER UNE EPREUVE</h2>
<form action="index.php?action=importer_xls" method="post" enctype="multipart/form-data">
    <div class="field">
        <label for="file" class="label">Nom de la station</label>
        <div class="control">
            <input class="input" type="file" name="file" id="file">
        </div>
    </div>
    <button type="submit" name="import_file" class="button is-info">Importer un fichier</button>
</form>


<br />
<div class="table-container">
    <table id="ski-table" class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
            <tr>
                <th>Station</th>
                <th>Date de l'épreuve</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th>Email</th>
                <th>Photo</th>
                <th>Catégorie</th>
                <th>Temps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($addSkiData as $datas){
                ?>
            <tr>
                <td><?= $datas['nom_station'] ?></td>
                <td><?= $datas['date_epreuve'] ?></td>
                <td><?= $datas['nom_participant'] ?></td>
                <td><?= $datas['prenom_participant'] ?></td>
                <td><?= $datas['date_naissance_participant'] ?></td>
                <td><?= $datas['email_participant'] ?></td>
                <td><img src="<?= $datas['photo_participant'] ?>" alt="competition ski" title="compétiton ski" </td>
                <td><?= $datas['nom_categorie'] ?></td>
                <td><?= $datas['temps_epreuve'] ?></td>
                <?php
                }
                ?>
            </tr>
        </tbody>
    </table>
</div>
<form action="index.php?action=exporter_xls" method="post">
    <div class="field">
    <input type="submit" value="Exporter en Fichier Excel"  name="export_btn" class="button is-success">
    </div>
</form>
<?php
$content = ob_get_clean();
require "template.php";