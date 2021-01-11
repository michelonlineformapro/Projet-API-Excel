<?php


class SkiModels
{
    function getPDO(){
        $user = "root";
        $pass = "";
        try {
            $db = new PDO("mysql:host=localhost;dbname=api_ski;charset=utf8;", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        }catch (PDOException $e){
            echo "Erreur de connexion à PDO MySQL : " .$e->getMessage();
        }
    }

    function generateExcelNoDb(){
        $datas = array(
            "0" => array(
                "nom_station" => "Les 2 Alpes",
                "date_epreuve" => "2020-12-01 13:00:00",
                "id_categorie" => "1",
                "temps" => 1.25
            ),
            "1" => array(
                "nom_station" => "Les 2 Alpes",
                "date_epreuve" => "2020-12-01 13:00:00",
                "id_categorie" => "1",
                "temps" => 1.12563
            ),
        );

        $filename = "competition-ski.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        $output = fopen('PHP://output', 'w');
        $header = array_keys($datas[0]);
        fputcsv($output, $header);
        foreach ($datas  as $row){
            fputcsv($output, $row);
        }
        fclose($output);
    }

    function getEpreuve(){
        $db = $this->getPDO();
        $sql = "SELECT * from competitions";
        $req = $db->query($sql);
        return $req;
    }

    function importCSV(){
        //Au click sur le bouton import excel
        if(isset($_POST['import_file'])){
            $filename = $_FILES['file']['tmp_name'];
            //La taille
            if($_FILES['file'] > 0){
                //Fichier concerner + mode lecture r = read
                $file = fopen($filename, "r");

                try {
                    $db = $this->getPDO();
                    $sql = "INSERT INTO competitions (nom_station, date_epreuve, nom_participant, prenom_participant, date_naissance_participant, email_participant, photo_participant, nom_categorie, temps_epreuve) VALUES (?,?,?,?,?,?,?,?,?)";
                    $req = $db->prepare($sql);
                    fgets($file);
                    while (($getData = fgetcsv($file, 10000, ',')) !== FALSE){
                        $req->bindParam(1, $getData[0], PDO::FETCH_ASSOC);
                        $req->bindParam(2, $getData[1],PDO::FETCH_ASSOC);
                        $req->bindParam(3, $getData[2],PDO::FETCH_ASSOC);
                        $req->bindParam(4, $getData[3],PDO::FETCH_ASSOC);
                        $req->bindParam(5, $getData[4],PDO::FETCH_ASSOC);
                        $req->bindParam(6, $getData[5],PDO::FETCH_ASSOC);
                        $req->bindParam(7, $getData[6],PDO::FETCH_ASSOC);
                        $req->bindParam(8, $getData[7], PDO::FETCH_ASSOC);
                        $req->bindParam(9, $getData[8],PDO::FETCH_ASSOC);
                        $req->execute($getData);
                        return $req;
                    }
                    fclose($file);

                }catch (PDOException $e){
                    echo "Erreur : " .$e->getMessage();
                }

            }
        }
    }

    function cleanData($str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }


    function getEpreuveExcel()
    {
        $db = $this->getPDO();
        $sql = "SELECT * FROM competitions";
        $req = $db->query($sql);
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="../Datas/CSV/competition-ski.csv"');

        $csv = fopen("php://output", 'w');
        $flag = false;
        foreach ($datas as $row) {
            if(!$flag){
                echo implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode("\t", array_values($row) . "\n");
        }
        exit();
    }
///////////////////////TEST////////////////////
/*
    function getEpreuveExcelMultiJoin()
    {
        $db = $this->getPDO();
        $sql = "SELECT * from epreuve INNER JOIN participant ON participant.id = epreuve.id_participant INNER JOIN categories ON categories.id = epreuve.id_categorie INNER JOIN passage ON passage.id = epreuve.id_temps";
        $req = $db->query($sql);
        $datas = $req->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="../Datas/CSV/competition-ski.csv"');

        $csv = fopen("php://output", 'w');
        $flag = false;
        foreach ($datas as $row) {
            if(!$flag){
                echo implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode("\t", array_values($row) . "\n");
        }
        exit();
    }

    function testJsonCsv(){
        // How to Generate CSV File from Array in PHP Script
        $results = array (
            "0" => array(
                "name"           => "Anna Smith",
                "email_id"      => "annabsmith@inbound.plus"
            ),
            "1" => array(
                "name"           => "Johnny Huck",
                "email_id" => "johnnyohuck@inbound.plus"
            )
        );
        $filename = 'userData.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        $output = fopen("php://output", "w");
        $header = array_keys($results[0]);
        fputcsv($output, $header);
        foreach($results as $row)
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
*/

}