<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter - Modifier: État</title>
  <link rel="stylesheet" type="text/css" href="../css/GSS/css/styler1-form.css">
  <link rel="stylesheet" type="text/css" href="../css/GSS/css/styler1-grid.css">
  <link rel="stylesheet" type="text/css" href="../css/GSS/css/styler1-text.css">
  <link rel="stylesheet" href="../css/Bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/scriptcommune.js"></script>
		<script type="text/javascript" src="../js/scripttarif.js"></script>
		<script type="text/javascript" src="../js/scriptpaiement.js"></script>
		<script type="text/javascript" src="../js/scriptsociete.js"></script>
</head>
<body>
<br><div class="container bg-light">	
<?php
	include '../all.class.inc.php';
	if ($_POST['action'] == 'modifier')
	{
		$ob = new Fournisseur($_POST['idfournisseur']);
		$ob->GetByID($conn);
		$action = 'modifier';
			echo "<p class='size-18 text-center'><b>Modifier la catégorie ",$ob->Getnomfournisseur(), "</p><br>";
	}
	else
	{
		$ob = new Fournisseur();
		$action = 'ajouter';
			echo "<p class='size-18 text-center'><b> Ajouter une nouvelle Catégorie </b></p><br>";
	}
?>
<div class="container">
<form method='POST' action='traitementfournisseur.php'>
  <div class="row">
	<input name='idfournisseur' type='hidden' value="<?php echo $ob->Getidfournisseur(); ?>"/>
		<div class="col-6">Nom:
			<input name='nomfournisseur' type='text' value="<?php echo $ob->Getnomfournisseur(); ?>"/>
		</div>
		<div class="col-6">Adresse:
			<input name='adressefournisseur' type='text' value="<?php echo $ob->Getadressefournisseur(); ?>"/>
		</div>
		<div class="col-6">Téléphone:
			<input name='telephonefournisseur' type='text' value="<?php echo $ob->Gettelephonefournisseur(); ?>"/>
		</div>
		<div class="col-6">Email:
			<input name='emailfournisseur' type='text' value="<?php echo $ob->Getemailfournisseur(); ?>"/>
		</div>
	    <div class="col-6 text-bold">Commune:
				<input name="libcommune" type="text" id="nom_id" onkeyup="autocompletC()" value='<?php echo $ob->Getlibcommune(); ?>' placeholder="libcommune" required>
				<input name="idcommune" type="hidden" id="nom2_id" name="idcommune" value='<?php echo $ob->Getidcommune(); ?>'>
				<ul id="nom_list_id"></ul>
      </div>
		<div class="col-6">Préfixe:
			<input name='prefixefournisseur' type='text' value="<?php echo $ob->Getprefixefournisseur(); ?>"/>
		</div>
		<div class="col-6 text-bold">Tarif:
    	<input type="text" id="nom_idtarif" onkeyup="autocompletT()" placeholder="Format numérique" value='<?php echo $ob->Getlibtarif(); ?>' required/>
      <input type="hidden" id="nomtarif_idtarif" name="idtarif" value='<?php echo $ob->Getidtarif(); ?>'>
      <ul id="nom_list_idtarif"></ul>
    </div>
		<div class="col-6 text-bold">Type de société:
			<input type="text" id="nom_idsociete" onkeyup="autocompletS()" placeholder="Société ..." value='<?php echo $ob->Getlibsociete(); ?>' required>
      <input type="hidden" id="nom2_idsociete" name="idsociete" value='<?php echo $ob->Getidsociete(); ?>'>
      <ul id="nom_list_idsociete"></ul>
    </div>
	  <div class="col-6 text-bold">Type de paiement:
			<input type="text" id="nom_idpaiement" onkeyup="autocompletP()" placeholder="paiement" value='<?php echo $ob->GetlibPaiement(); ?>' required>
      <input type="hidden" id="nom2_idpaiement" name="idpaiement" value='<?php echo $ob->GetidPaiement(); ?>'>
      <ul id="nom_list_idpaiement"></ul>
    </div>
		<div class="col-6">Code:
			<input name='codefournisseur' type='text' value="<?php echo $ob->Getcodefournisseur(); ?>"/>
		</div>
  </div>
	<input name='action' type='hidden' value="<?php echo $action; ?>"/>
	<a class='button bg_dark-radius_6' href="fournisseur.vue.php">Retour</a>
	<button type='submit' class='button bg_green-radius_6'>Sauvegarder</button>
</form>
</div>
</body>
</html>