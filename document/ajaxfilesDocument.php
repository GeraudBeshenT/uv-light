<?php

include '../bdd.class.inc.php';
include '../all.class.inc.php';

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

$searchArray = array();

$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (iddocument LIKE :iddocument "
            . "OR datedoc LIKE :datedoc "
            . "OR statut LIKE :statut "
            . "OR commentaire LIKE :commentaire ";
    $searchArray = array(
        'iddocument' => "%$searchValue%",
        'datedoc' => "%$searchValue%",
        'statut' => "%$searchValue%",
        'commentaire' => "%$searchValue%"
    );
}

$ob = new Document();

$totalRecords = $ob->CountBDD($conn);
$totalRecordwithFilter = $ob->CountParamBDD($conn,$searchQuery,$searchArray);

$stmt = $conn->prepare("SELECT * FROM document INNER JOIN etat ON etat.idetat = document.idetat INNER JOIN fournisseur ON document.idfournisseur = fournisseur.idfournisseur WHERE supdocument = 0 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int) $row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
// Boucle modifier supprimé qui se répète sur toutes les lignes du tableau.
// Récupération de l'idfournisseur, formulaire de modification/suppression qui envoient les données dans les fichiers modifournisseur/tratiementfournisseur.
foreach ($empRecords as $row) {
    if ($row['supdocument'] == 0) {
        $data[] = array(
            "iddocument" => $row['iddocument'],
            "datedoc" => $row['datedoc'],
            "statut" => $row['statut'],
            "commentaire" => $row['commentaire'],
            "idetat" => $row['idetat'],
            "libetat" => $row['libetat'],
            "idfournisseur" => $row['idfournisseur'],
            "nomfournisseur" => $row['nomfournisseur'],
            "actions" => "<div class='btn-group'>"
            . "<form method='POST' action='modifDocument.php'>"
                . "<button type='submit' class='btn btn-primary rounded-pill'><i class='fa fa-edit'></i></button>"
                . "<input name='iddocument' type='hidden' value='" . $row['iddocument'] . "'/>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='action' type='hidden' value='modifier'/>"
            . "</form> &nbsp"
            ."<form method='POST' action='traitementDocument.php'>"
                . "<button type='submit' class='btn btn-danger rounded-pill'><i class='fa fa-trash'></i></button>"
                . "<input name='iddocument' type='hidden' value='" . $row['iddocument'] . "'/>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='action' type='hidden' value='supprimer'/>"
            . "</form></div>",
        );
    }
}

$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
?>