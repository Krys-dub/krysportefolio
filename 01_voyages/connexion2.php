
<?php
/* A revoir:  recharger la page = nouvelle insertion ??? pseudo unique , avoir les mess erreur dans le formulaire, conserver le remplissage meme si erreur */
        try
        {
            $bdd= new PDO('mysql:host=localhost;dbname=essais;charset=UTF8','root','root');
        }
            catch (Exception $e)
            {
                die('Erreur: ' .$e->getMessage());
            }   

     
    
 if(isset ($_POST['valider']))
    {  
     
     $prenom = htmlspecialchars($_POST['prenom']);
$nom =  htmlspecialchars($_POST['nom']);
$email =  htmlspecialchars ($_POST['email']);
$speudo = htmlspecialchars($_POST['pseudo']);
$pass = htmlspecialchars($_POST['pass']); 
$identic_pass = htmlspecialchars($_POST['identic_pass']);
/*$valider = $_POST['valider']; 
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
    return $donnees; }
    */
    
 
    if (isset($_POST['prenom'])and !empty($_POST['prenom']) and isset($_POST['nom'])and empty($_POST['nom']))
    {
        echo 'Renseigner prénom et nom<br />';
    }
   
//  PSEUDO   
    if(isset($_POST['pseudo']) and !empty($_POST['pseudo']))  
    {    
        $req = $bdd ->prepare('SELECT pseudo COUNT (*) FROM voyagers WHERE pseudo = :pseudo');
        $pseudo=htmlspecialchars($_POST['pseudo']);
        $req ->execute();
        $resultat=$req -> fetch();
       
           if ($resultat != 0)
           { 
               echo 'Pseudo déjà pris<br />';
              $req->closeCursor();
         }         
    }
        
// Traitement mail
    elseif (isset($_POST['email']) and !empty($_POST['email']))
    {       
            //$email = $_POST['email'];
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email));
        {
          echo "Format email invalide<br />";   
        }                      
    }                          
// Traitement mot de passe
    elseif (isset($_POST['pass'])and empty($_POST['pass']) and (isset($_POST['identic_pass'])and empty($_POST['identic_pass'])))
        {
            echo '<p>Renseignez les mots de passe</p>';
        }
        $pass =$_POST['pass']; $identic_pass= $_POST['identic_pass'];
        if ($pass != $identic_pass)
             
            {
                echo '<p>Les mots de passe doivent être identiques.</p>';
            }         
            
        if (!preg_match("#^[a-zA-Z0-9.@\#_-]{8,}$#", $pass))
        {
            echo "<p>Format mot de passe incorrect<br /></p>";
        }                   
 
            else       
 //ENVOIE VERS LA TABLE          
        {
        // hachage mot de passe
    $pass = $_POST['pass'];
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $donnees=[
    'prenom'=>htmlspecialchars($_POST['prenom']),
    'nom'=>htmlspecialchars($_POST['nom']),
    'email'=>htmlspecialchars($_POST['email']),
    'pseudo'=>htmlspecialchars($_POST['pseudo']),
    'password'=>$pass_hash];
                
        $req=$bdd->prepare('INSERT INTO voyagers (prenom, nom, email, pseudo, password) VALUES(:prenom, :nom, :email, :pseudo, :password)');
        $req->execute($donnees);
        echo '<p>Vous êtes membre!!</p>';
    
      
            $req->closeCursor(); }
    
 }
   
?>             
             
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel='stylesheet' href='style.css' type="text/css"/>
        <title>Mon site Mes voyages</title>
    </head>
    <body>
        <header>
        <?php include ("menu_header.php"); ?>
        </header>
        <section>
              <a  href='index.php' class='lien_page'>Retour à l'accueil</a>
        <div id='inscription_connexion'>
            <div id='inscription'>
                <h4>Pas encore inscrit ? C'est ici !</h4>
        <form action="connexion2.php" method="POST" >
                <label for="prenom">Prénom: </label><input type="text" name="prenom" id='prenom'/><br />
                <label for="nom">Nom: </label><input type='text' name='nom' id='nom'/><br />
                <label for="email">Email: </label><input type="email" name='email' id='email'/><br />
                <label for="pseudo">Pseudo: </label><input type="text" name="pseudo"  id='pseudo'/><br />
                <label for="pass">Mot de passe: </label><input type="password" name="pass"  class='box_connexion'/><br />
                <label for="confirmed_pass">Confirmez votre mot de passe: </label><input type="password" name="identic_pass" id='identic_pass'/><br />
                <p><em>"Mot de passe: minimum 8 caractères, .@\#_- autorisés"</em></p>
                <input type='submit' name="valider" value='valider'/>
        </form>
                </div>
            
            <div id='connexion'>
                <h4>Déja membre? Super !</h4>
            <form action="traitement_connexion.php" method="POST" >
                <label for="pseudo">Pseudo: </label><input type="text" name="pseudo" class='box_connexion' /><br />
                <label for="pass">Mot de passe: </label><input type="password" name="pass" class='box_connexion' /><br />
                 <input type='submit' name="Entrer" value='valider'/>
                
                </form>
            </div>
           </div> 
           
  
         
     
         </section>
        <footer>
        <?php include ("menu_footer.php"); ?>
        </footer>    
    </body>
</html>