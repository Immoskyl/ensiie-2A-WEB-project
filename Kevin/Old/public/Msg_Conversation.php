<?php
function affiche_message($message){
    return 'De '.$message->getEmetteur().' à '.
    ($message->getDate())->format('H:i:s').' le '.
    ($message->getDate())->format('Y-m-d').' : '.$message->getContenu();
}

require '../vendor/autoload.php';

//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");



/**************AJOUT ******************/
function prenom_user($id_user){
    global $connection;
    $sth = $connection->prepare('SELECT * FROM "user" WHERE id=\''.$id_user.'\';');
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_OBJ);
    return $result->firstname;
}

//$msgManager = new \Message\MessageManager($connection);

$emetteur = $_GET['emetteur'];
$recepteur = $_GET['recepteur'];


$sth = $connection->prepare('SELECT * FROM "message" WHERE (emetteur=\''.$emetteur.'\' AND recepteur=\''.$recepteur.'\') OR (emetteur=\''.$recepteur.'\' AND recepteur=\''.$emetteur.'\')');
$sth->execute();
$result = $sth->fetch(PDO::FETCH_OBJ);     

$Res = "";
while($result){
	$msg = new \Message\Message();
	if (strcmp($result->emetteur, $emetteur)){
        $msg->setEmetteur("Moi");
	}
	else{
        $msg->setEmetteur($result->emetteur);
    }
	$msg->setDate(new \DateTime($result->date_envoie))
		->setContenu(prenom_user($result->contenu));

	$Res .= affiche_message($msg);
	
	$result = $sth->fetch(PDO::FETCH_OBJ);
	if($result){
		$Res .= ',,';
	}
}
echo($Res);
?>

