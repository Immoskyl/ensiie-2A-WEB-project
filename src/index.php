<?php
include("authentication.php"); 
include("viewfunctions.php");

header("Hello"); 
if(verif_authent()){ // si le gars est authentified ==>  acces aux offres
	indexco();
}

else {	// sinon pas acces aux offres
	indexnotco();
}
footer();
?>
