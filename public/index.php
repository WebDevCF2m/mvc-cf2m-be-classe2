<?php
# 1 ) Ce fichier est le point d'entrée de l'application, il est appelé par le serveur web (Apache, Nginx, IIS, etc.) et il va charger les fichiers nécessaires pour exécuter l'application. - Comment l'appelle-t-on ? | Contrôleur Frontal

# 2) que lance-t-on à cette ligne ? | Démare une session lors du premier affichage, crée un cookie de session (PHPSESSID) dans le navigateur et un fichier temporaire sur le serveur qui est vide au lencement. Lors des affichages suivant, continue la session si une session valide existe déjà (les variables ne sont jamais stockées coté utilisateur, mais côté serveur)
session_start();

# 3) que charge-t-on à cette ligne ?
require_once "../config.php";
# 4) que charge-t-on à ces lignes ?
require_once "../model/PostModel.php";# table post
require_once "../model/CategoryModel.php";# table category
require_once "../model/UserModel.php";# table user


# 5 ) Nous essayons de lancer quelle type d'objet, et pourquoi?
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD
    );
        # 6 ) activation de quoi
        $connectPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # 7 ) En quoi voulons-nous que les résultats soient retournés par défaut ? 
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