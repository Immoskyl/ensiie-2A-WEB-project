<?php
session_start();
require '../vendor/autoload.php';
include('menu.php');
//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$users = $userRepository->fetchAll();
?>

<html>
<head>
    <title> Recettes  </title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"  href="style_index.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<?php
menu_aperal();
?>

<br />
<br />
<br />
<h3><?php echo 'Recettes' ?></h3>

    <table class="table table-bordered table-hover table-striped">
        <thead style="font-weight: bold">
        <td>Id de la recette</td>
        <td>Nom de la recette</td>
        <td>Note</td>
        </thead>
        <?php /** @var \User\User $user */
    	  $irec=$connection->query("SELECT * FROM public.note")->fetchAll();
    	  $j=1;
    	  foreach($irec as $id){
       			 $j++;
   			 }  
    if(isset($_POST['note']) && $_SESSION['connect']>=1){
    	echo $j;
        $req=$connection->prepare('INSERT INTO public.note(note,id_rec,id_vente,id_usr) VALUES(:note,:id_rec,:id_vente,:id_usr)');
        $req->execute(['note'=>$_POST['note'],
            'id_rec'=>$_POST['id'],
            'id_vente' => $j,
            'id_usr' => $_SESSION['id'],
            ]);
    }

        $turec=$connection->query("SELECT * FROM public.recette")->fetchAll();

        if(!empty($turec)){
        	foreach ($turec as $res){
        		$id_recette=$res['id_rec'];
        	    $tunote=$connection->query("SELECT AVG(note) AS moyenne FROM public.note WHERE id_rec=$id_recette")->fetch();

        	?>
           		<tr>
               		<td><?php echo $res['id_rec'] ?></td>
               		<td><?php echo $res['recettes'] ?></td>
               		<td><?php echo $tunote['moyenne'];?></td>

            		</tr>
        	<?php }	
        }
        
?>
    </table>
    <?php 
    echo '<div class="gtco-container">';

    echo '<div class="form-c">';
    echo '<div class="form-c-head">Noter une recette :</div>';
    echo '<form method = "post" action="#">';
    echo '<label for="recette"><span class="txt">ID de la Recette <span class="required">*</span></span><input type="text" class="input-field" name="id" /></label>';
    echo '<label for="note"><span class="txt">Note <span class="required">*</span></span><input type="number" class="input-field" name="note" min=0 max=5 /></label>';
    echo '<input type ="submit" name="submit" value="Noter"/>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';


    ?>
  </body>

