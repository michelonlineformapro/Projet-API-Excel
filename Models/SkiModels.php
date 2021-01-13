<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/////////////////////////*TABLE CATEGORIES////////////////////////////////////////

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

    //Liste des épreuves sur le site
    function getEpreuve(){
        $db = $this->getPDO();
        $sql = "SELECT * FROM competitions";
        $req = $db->query($sql);
        return $req;
    }


//Import de fichier xlsx avec PhpSpreadSheet
    function importSpreadSheet()
    {
        $spreadsheet = new Spreadsheet();
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spedSheet = $reader->load('../Datas/ski.xlsx');
        $worksheet = $spedSheet->getActiveSheet();
        $isheader = 0;
        $sql = "INSERT INTO competitions(nom_station, date_epreuve, nom_participant, prenom_participant, date_naissance_participant, email_participant, photo_participant, nom_categorie, temps_epreuve) VALUES (?,?,?,?,?,?,?,?,?)";
        foreach ($worksheet->getRowIterator() as $row) {
            if ($isheader > 0){
                $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }
                //Insertion
                try {
                    $db = $this->getPDO();
                    $stmt = $db->prepare($sql);
                    $stmt->execute($data);
                    echo "OK {$db->lastInsertId()}<br>";
                } catch (PDOException $e) {
                    echo "Erreur :" . $e->getMessage();
                }
        }else{
        $isheader = 1;

        $stmt = null;
    }
    }

    }


    public function exportXLSX(){
        $db = $this->getPDO();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet()->setTitle("Compétition Ski");

        $activeSheet->setCellValue('A1', 'station');
        $activeSheet->setCellValue('B1', 'date_epreuve');
        $activeSheet->setCellValue('C1', 'nom_participant');
        $activeSheet->setCellValue('D1', 'prenom_participant');
        $activeSheet->setCellValue('E1', 'date_naissance_participant');
        $activeSheet->setCellValue('F1', 'email_participant');
        $activeSheet->setCellValue('G1', 'photo_participant');
        $activeSheet->setCellValue('H1', 'nom_categorie');
        $activeSheet->setCellValue('I1', 'temps_epreuve');

        $sql = "SELECT * FROM competitions";
        $query = $db->prepare($sql);
        $query->execute();

        $i = 8;

            while ($row = $query->fetch()) {
                $activeSheet->setCellValue('A' . $i, $row['nom_station']);
                $activeSheet->setCellValue('B' . $i, $row['date_epreuve']);
                $activeSheet->setCellValue('C' . $i, $row['nom_participant']);
                $activeSheet->setCellValue('D' . $i, $row['prenom_participant']);
                $activeSheet->setCellValue('E' . $i, $row['date_naissance_participant']);
                $activeSheet->setCellValue('F' . $i, $row['email_participant']);
                $activeSheet->setCellValue('G' . $i, $row['photo_participant']);
                $activeSheet->setCellValue('H' . $i, $row['nom_categorie']);
                $activeSheet->setCellValue('I' . $i, $row['temps_epreuve']);
                $i++;
            }
        $excel_writer = new Xlsx($spreadsheet);
        $excel_writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $filename = 'ski.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename);
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $excel_writer->save('php://output');
       }


       //TABLE ET LES JOINTURES
    function getAllForeingnKeysDatas(){
        $db = $this->getPDO();
        $sql = "SELECT * FROM epreuve INNER JOIN participant ON participant.id = epreuve.id_participant INNER JOIN  categories ON categories.id = epreuve.id_categorie INNER JOIN passage ON passage.id = epreuve.id_temps";
        $req = $db->query($sql);
        return $req;
    }

    //Insert into compétitions
    function  createCompetition(){
        $db = $this->getPDO();
        $sql = "INSERT INTO competitions(nom_station, date_epreuve, nom_participant, prenom_participant, date_naissance_participant, email_participant, photo_participant, nom_categorie, temps_epreuve) VALUES (?,?,?,?,?,?,?,?,?)";
        if(isset($_POST['nom_station'])){
            $station = $_POST['nom_station'];
        }

        $req = $db->prepare($sql);
        $req->bindParam(1, $station);

    }


    //TABLES CATEGORIE

    function getAllCategories(){
        $db = $this->getPDO();
        $sql = "SELECT * FROM categories";
        $req = $db->query($sql);
        return $req;
    }

    //TABLES participant

    function getAllParticipant(){
        $db = $this->getPDO();
        $sql = "SELECT * FROM participant";
        $req = $db->query($sql);
        return $req;
    }

    //TABLES passage

    function getAllPassage(){
        $db = $this->getPDO();
        $sql = "SELECT * FROM passage";
        $req = $db->query($sql);
        return $req;
    }







}


