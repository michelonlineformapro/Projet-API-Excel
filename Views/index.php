<?php
require "../Controllers/SkiController.php";

//Routing
if(isset($_GET['action'])){
    if($_GET['action'] === "ski"){
        $title = "SKI API EXPORTER CSV";
        exportAsExcelFile();
    }elseif ($_GET['action'] === "accueil"){
        $title = "SKI API ACCUEIL";
        displaySkiDatas();
    }elseif ($_GET['action'] === "importer_xls"){
        importCsvData();
    }elseif ($_GET['action'] === "exporter_xls"){
        exportAsExcelFile();
    }elseif ($_GET['action'] === "creer_epreuve"){

    }
}

