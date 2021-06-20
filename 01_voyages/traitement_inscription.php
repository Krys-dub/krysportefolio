 <?php
        try
        {
            $bdd= new PDO('mysql:host=localhost;dbname=essais;charset=UTF8','root','root');
        }
            catch (Exception $e)
            {
                die('Erreur: ' .$e->getMessage());
            }   

/* methode de sécurisation 1
$prenom = valid_donnees ($_POST['prenom']);
$nom =  valid_donnees ($_POST['nom']);
$email =  valid_donnees ($_POST['email']);
$speudo = valid_donnees ($_POST['pseudo']);
$pass = valid_donnees ($_POST['pass']); 
$identic_pass = valid_donnees ($_POST['identic_pass']);

function valid_donnees($donnees){
    $donnees = trim ($donnees);
    $donnees = stripslashes ($donnees);
    $donnees = htmlspecialchars ($donnees);
    return $donnees;    */

// méthode sécurisation 2
function securite_bdd($string)
{
    if(ctype_digit($string))
    {
        $string = intval($string);
    }
    else 
    {
        $string = mysql_real_escape_string($string);
        $string = addslashes($string,'%_');
}
    return $string;
}

$prenom = securite_bdd ($_POST['prenom']);
$nom = securite_bdd ($_POST['nom']);
$email = securite_bdd ($_POST['email']);
$pseudo = securite_bdd ($_POST['pseudo']);
$pass = securite_bdd ($_POST['pass']);
$identic_pass = securite_bdd ($_POST['identic_pass']);

/* envoi au serveur sécurisé: 
mysql_query($nomRequête) or exit(mysql_error()); */

    if(!empty($prenom) and !empty($nom) 
       and !empty($email) and preg_match ("#^[a-z0-9._-]+@[a-z]{2,}\.[a-z]{2,4}$#", $email)
       and !empty($pseudo)
       and !empty($pass) and preg_match ("#^[a-zA-Z0-9#@/!-]{8,}$#",$pass)
       and ($pass == $identic_pass)) 
    {
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
         
        $req = $bdd ->prepare('INSERT INTO voyagers (prenom, nom,email,pseudo, password) VALUES (:prenom, :nom, :email, :pseudo, :password)');
        $entrees=[
        'prenom' => $_POST['prenom'],
        'nom' => $_POST['nom'],
        'email' => $_POST['email'],
        'speudo' => $_POST['pseudo'],
        'password' => $pass_hash]; 
$identic_pass = valid_donnees ($_POST['identic_pass']);
        $req ->execute($entrees);
        echo 'Vous êtes inscrit(e)'; 
        header ('location:connexion2.php');
        $req->closeCursor();
    }
else
{
    echo 'L\'inscription a échoué';
    header ('location:connexion2.php');
}










?>
