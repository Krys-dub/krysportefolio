 <?php
session_start();
$_SESSION['pseudo']=htmlspecialchars($_POST['pseudo']);

        try
        {
            $bdd= new PDO('mysql:host=localhost;dbname=essais;charset=UTF8','root','root');
        }
            catch (Exception $e)
            {
                die('Erreur: ' .$e->getMessage());
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
      <section>     
        <?php
if(isset($_POST['valider'])) 
{
        if(isset($_POST['pseudo']) and isset($_POST['pass']))
        {           
              $pseudo=$_POST['pseudo'];
              $pass= $_POST['pass'];
              $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
                   
              $req=$bdd ->prepare('SELECT pseudo, password FROM voyagers WHERE pseudo = :pseudo');
              $resultat =$bdd ->execute();
              
              $resultat=$req->fetch();
                   
            if($resultat >0)               
              {
                  if(password_verify($pass,$req['password']))
                  {
                      $_SESSION['pseudo'] = $req['pseudo'];
                      echo 'Vous êtes connecté';
                      header('location:connexion2.php');
                      $req->closeCursor;
                  }                 
            }else{
                  echo '<p>Pseudo ou mot de passe erronés!</p>';
                  header('location:connexion2.php');
              }
        }else {
                  echo 'remplissez tous les champs';
              }
          
}
         
  
       ?>
            
         </section>
        <footer>
        <?php include ("menu_footer.php"); ?>
        </footer>    
    </body>
</html>