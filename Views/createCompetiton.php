<?php

?>
<form action="" method="post">
    <h1 class="is-size-1 title is-info has-text-centered">Creer une compétition</h1>

    <div class="field">
        <label class="label">Nom de la station</label>
        <div class="control">
            <input class="input" type="text" name="nom_station" placeholder="Nom de la station">
        </div>
    </div>

    <div class="field">
        <label for="date_epreuve">Date de l'épreuve</label>
        <input class="input" type="date" id="date_epreuve" name="date_epreuve"">
    </div>

    <div class="field">
        <label for="nom_participant">Nom de participant</label>
        <?php
        $user = "root";
        $pass = "";
        $db = new PDO("mysql:host=localhost;dbname=api_ski;charset=utf8;", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM participant";
        $req = $db->query($sql);
        ?>
        <br />
        <div class="select is-multiple">
        <select name="nom_categorie" multiple>
            <?php
            foreach ($req as $row){
                ?>
                <option value="<?php $row["nom_participant"] ?>"><?= $row["nom_participant"] ?></option>
                <?php
            }
            ?>
        </select>
        </div>
    </div>

    <?php
    $nombre = 15;


    if(is_int($nombre)){
        echo $nombre;
    }else{
        echo $nombre . "Nes pas un nombre netier";
    }


    ?>


    <!--
    <div class="field">
        <label class="label">Nom de la categories</label>
        <div class="control">
            <div class="select">
                <select name="nom_categorie[]" multiple>
                    <option value="1">M1 et F1 - 31/35 ans</option>
                    <option value="2">M2 et F2 - 36/40 ans</option>
                    <option value="3">M3 et F3 - 41/45 ans</option>
                    <option value="4">M4 et F4 - 46/50 ans</option>
                    <option value="5">M5 et F5 - 51/55 ans</option>
                    <option value="6">M6 et F6 - 56/60 ans</option>
                    <option value="7">M7 et F7 - 61/65 ans</option>
                    <option value="8">M8 et F8 - 66/70 ans</option>
                    <option value="9">M9 et F9 - 71/75 ans</option>
                    <option value="10">M10 et F10 - 76/80 ans</option>
                </select>
            </div>
        </div>
    </div>
    -->

    <button type="submit" class="button is-info">Valider</button>
</form>


<?php
/*
var_dump($_POST['nom_station']);
var_dump($_POST['date_epreuve']);
var_dump($_POST['nom_participant']);
var_dump($_POST['prenom_participant']);
var_dump($_POST['date_naissance_participant']);
var_dump($_POST['email_particpant']);
var_dump($_POST['photo_participant']);
var_dump($_POST['nom_categorie']);
var_dump($_POST['temps_epreuve']);
