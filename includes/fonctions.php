<?php
/**
 * @param string $err
 */
function erreur($err='')
{
    $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
    exit("<p>".$mess.'</p> <p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil</p></div></body></html>');
}

function move_avatar($avatar)
{
    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
    $name = time();
    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
    $name = "./avatars/".str_replace(' ','',$name).".".$extension_upload;
    move_uploaded_file($avatar['tmp_name'],$name);
    return $nomavatar;
}

function move_posts($avatar)
{
    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
    $name = time();
    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
    $name = "./posts/".str_replace(' ','',$name).".".$extension_upload;
    move_uploaded_file($avatar['tmp_name'],$name);
    return $nomavatar;
}

function aff_posts($id_posts)
{
    $db = new PDO('mysql:host=localhost;dbname=golriie', 'root', '');
    $query = $db->prepare('SELECT titre,description,img,jaime,nul,author FROM posts WHERE id=:id');
    $query->bindValue(':id', $id_posts, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $titre = $data["titre"];
    $desc = $data["description"];
    $jaime = $data["jaime"];
    $nul = $data["nul"];
    $img = $data["img"];
    $author = $data["author"];
    $query->CloseCursor();
    $query = $db->prepare('SELECT pseudo FROM membres WHERE id=:id');
    $query->bindValue(':id', $author, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $author = $data["pseudo"];
    echo '<div> <h1>' . $titre . '</h1><br />
            <img src="./posts/' . $img . '"alt="" /><br />' . $desc . '<br />' .
        $jaime . '+ et ' . $nul . '-<br /> Par : ' . $author.'<br /> ---------------------<br />';
}

?>