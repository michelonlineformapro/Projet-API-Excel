<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;

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


    //Insertion des données depuis le fichier ski.xlsx a la mano
    function importXslX(){

        //Instance de la classe + nom du fichier
        $xlsx = new SimpleXLSX('../Datas/ski.xlsx');
        var_dump($xlsx);
        $db = $this->getPDO();
        $req = $db->prepare("INSERT INTO competitions(nom_station, date_epreuve, nom_participant, prenom_participant, date_naissance_participant, email_participant, photo_participant, nom_categorie, temps_epreuve) VALUES (?,?,?,?,?,?,?,?,?)");
        $req->bindParam(1, $nom_station);
        $req->bindParam(2, $date_epreuve);
        $req->bindParam(3, $nom);
        $req->bindParam(4, $prenom);
        $req->bindParam(5, $date_naissance);
        $req->bindParam(6, $email);
        $req->bindParam(7, $photo);
        $req->bindParam(8, $categorie);
        $req->bindParam(9, $temps_epreuve);

        foreach ($xlsx->rows() as $fields){
            $nom_station = $fields[0];
            $date_epreuve = $fields[1];
            $nom = $fields[2];
            $prenom = $fields[3];
            $date_naissance = $fields[4];
            $email = $fields[5];
            $photo = $fields[6];
            $categorie = $fields[7];
            $temps_epreuve = $fields[8];
            $req->execute();
        }
    }


//Import de fichier xlsx avec PhpSpreadSheet
    function importSpreadSheet()
    {
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


    //Export au format web à transformer en xlsx a la mano
    function export_xls_file()
    {
        $db = $this->getPDO();
        $sql = "SELECT * FROM competitions";
        $datas = $req = $db->query($sql);

        echo '<meta charset="UTF-8"><table border="1">';
//make the column headers what you want in whatever order you want
        echo "<thead>
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
              </thead>";
//loop the query data to the table in same order as the headers
        while ($row = $datas->fetch(PDO::FETCH_ASSOC)){
            echo "<tbody>
                    <tr>
                        <td>".$row['nom_station']."</td>
                        <td>".$row['date_epreuve']."</td>
                        <td>".$row['nom_participant']."</td>
                        <td>".$row['prenom_participant']."</td>
                        <td>".$row['date_naissance_participant']."</td>
                        <td>".$row['email_participant']."</td>
                        <td>".$row['photo_participant']."</td>
                        <td>".$row['nom_categorie']."</td>
                        <td>".$row['temps_epreuve']."</td>
                    </tr>
                 </tbody>";
        }
        echo '</table>';
        $file = "competiton_ski.xls" ;
        header('Content-Disposition: attachment; filename=' . $file );
        header('Content-Type: application/xlsx');
    }

    public function exportXLSX()
    {
        $db = $this->getPDO();

        $spreadsheet = new Spreadsheet();
        $excel_writer = new Xlsx($spreadsheet);
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


        $filename = date("Y") . ' ' . 'competition_ski.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename);
        header('Cache-Control: max-age=0');
        $excel_writer->save('php://output');


       }

}