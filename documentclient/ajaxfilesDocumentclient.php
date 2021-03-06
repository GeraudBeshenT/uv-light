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
    $searchQuery = " AND (iddocumentclient LIKE :iddocumentclient "
            . "OR statutclient LIKE :statutclient "
            . "OR commentaireclient LIKE :commentaireclient ";
    $searchArray = array(
        'iddocumentclient' => "%$searchValue%",
        'statutclient' => "%$searchValue%",
        'commentaireclient' => "%$searchValue%"
    );
}

$ob = new Documentclient();

$totalRecords = $ob->CountBDD($conn);
$totalRecordwithFilter = $ob->CountParamBDD($conn,$searchQuery,$searchArray);

$stmt = $conn->prepare("SELECT * FROM documentclient INNER JOIN etat ON etat.idetat = documentclient.idetat INNER JOIN client ON documentclient.idclient = client.idclient WHERE supdocumentclient = 0 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int) $row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
// Boucle modifier supprimé qui se répète sur toutes les lignes du tableau.
// Récupération de l'idclient, formulaire de modification/suppression qui envoient les données dans les fichiers modiclient/tratiementclient.
foreach ($empRecords as $row) {
    if ($row['supdocumentclient'] == 0) {
        $data[] = array(
            "iddocumentclient" => $row['iddocumentclient'],
            "datedocclient" => $row['datedocclient'],
            "statutclient" => $row['statutclient'],
            "commentaireclient" => $row['commentaireclient'],
            "idetat" => $row['idetat'],
            "libetat" => $row['libetat'],
            "idclient" => $row['idclient'],
            "nomclient" => $row['nomclient'],
            "actions" => "<div class='btn-group'>"
            . "<form method='POST' action='modifDocumentclient.php'>"
                . "<button type='submit' class='btn btn-primary rounded-pill'><i class='fa fa-edit'></i></button>"
                . "<input name='iddocumentclient' type='hidden' value='" . $row['iddocumentclient'] . "'/>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='action' type='hidden' value='modifier'/>"
            . "</form> &nbsp"
            ."<form method='POST' action='traitementDocumentclient.php'>"
                . "<button type='submit' class='btn btn-danger rounded-pill'><i class='fa fa-trash'></i></button>"
                . "<input name='iddocumentclient' type='hidden' value='" . $row['iddocumentclient'] . "'/>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='action' type='hidden' value='supprimer'/>"
            . "</form>"
            . "</div>",
            "detail" => "<div class='btn-group'>"
            . "<form method='POST' action='detailclient.vue.php?iddocumentclient=".$row['iddocumentclient']."'>"
                . "<button type='submit' class='btn btn-success rounded-pill'><i class='fa fa-eye'></i></button>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='iddocumentclient' type='hidden' value='" . $row['iddocumentclient'] . "'/>"
                . "<input name='action' type='hidden' value='modifier'/>"
            . "</form>"
            . "</div>",
            "export" => "<div class='btn-group'>"
            . "<form method='POST' action='./FPDF/pdfarticles.php' target='_blank'>"
                . "<button type='submit' class='btn btn-warning rounded-pill'>PDF</button>"
                . "<input name='iddocumentclient' type='hidden' value='" . $row['iddocumentclient'] . "'/>"
                . "<input name='type' type='hidden' value='Document'/>"
                . "<input name='action' type='hidden' value='modifier'/>"
            . "</form>"
            . "</div>",
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