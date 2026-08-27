<?php
# 1 ) Ce fichier est le point d'entrée de l'application, il est appelé par le serveur web (Apache, Nginx, IIS, etc.) et il va charger les fichiers nécessaires pour exécuter l'application. - Comment l'appelle-t-on ? | Contrôleur Frontal

# 2) que lance-t-on à cette ligne ? | Démare une session lors du premier affichage, crée un cookie de session (PHPSESSID) dans le navigateur et un fichier temporaire sur le serveur qui est vide au lencement. Lors des affichages suivant, continue la session si une session valide existe déjà (les variables ne sont jamais stockées coté utilisateur, mais côté serveur)
session_start();

# 3) que charge-t-on à cette ligne ? | On charge le fichier config.php qui contient des constantes (par exemple de connexion). Il n'est pas présent dans le dossier (.gitignore)
require_once "../config.php";

# 4) que charge-t-on à ces lignes ? | En procédural, on charge des fichiers de fonctions qui agissent sur les tables, on aurait pu ne charger qu'un fichier avec toutes les fonctions, mais on divise comme si on était en OO -> voir model/CommentModel.php en OO
require_once "../model/PostModel.php";# table post
require_once "../model/CategoryModel.php";# table category
require_once "../model/UserModel.php";# table user

// pour voir un modèle en classe on décommente
// require_once "../model/CommentModel.php";

// $comment1 = new CommentModel();
// echo $comment1->id;
// $comment1->id=180;
// echo $comment1->id;
// echo $comment1->getText();
// $comment1->setText('db');
// echo $comment1->getText();
// $comment1->setText('Bonjour les amis');
// echo $comment1->getText();


# 5 ) Nous essayons de lancer une connexion à notre base de donnée en utilisant la classe PDO qu'on instancie sous le nom de $connectPDO
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD
    );
        # 6 ) activation de quoi | Activation de l'affichage des erreurs pour les requêtes (débogage ou gestion des erreurs), Est par défaut depuis PHP 8.0
        $connectPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # 7 ) En quoi voulons-nous que les résultats soient retournés par défaut ? | On met par défaut les résultats des requêtes en tableau associatif (fetch et fetchAll (résultats dans un indexé contenant des valeurs associative))
        $connectPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    
# 8 ) le catch est appelé dans quel cas
}catch(Exception $e){
    # 9 ) et que fait-il?
    die($e->getMessage());

}


# Router

// ici sont redirigés les administrateurs connectés.
if(isset($_SESSION['myID'])&&$_SESSION['myID']==session_id()){
    require_once "../controller/privateController.php";
  
// zone publique, pour les visiteurs
}else{
    require_once "../controller/publicController.php";
}


# 10 )
$connectPDO = null;