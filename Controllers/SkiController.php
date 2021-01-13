<?php
require  "../Models/SkiModels.php";


function displaySkiDatas(){
    $ski_model = new SkiModels();
    $addSkiData = $ski_model->getEpreuve();
    require "../Views/accueil.php";
}

function importCsvData(){
    $ski_model = new SkiModels();
    $addSkiData = $ski_model->importSpreadSheet();
    require "../Views/import.php";
}

function exportAsExcelFile(){
    $ski_model = new SkiModels();
    $addExcelFile = $ski_model->exportXLSX();
    require "../Views/export.php";
}

function getAllTheForeinDatas(){
    $ski_model = new SkiModels();
    $getDatas = $ski_model->getAllForeingnKeysDatas();
    require "../Views/createCompetiton.php";
}


