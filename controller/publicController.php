<?php
/**
 * public Controller
 */


# 11 ) que récupère-t-on et de quel fichier provient cette fonction ?
# | fonction qui récupère toutes les catégories de la base de donnée
# pour créer le menu de navigation, il se trouve dans model/CategoryModel.php
$recupMenu = getAllCategoryMenu($connectPDO);

# 12 ) cette condition vérifie quoi ? | Si la variable get 'postId' existe
# et on vérifie si il n'y a que du numérique dans son contenu qui est par
# défaut une chaine de caractère (string)
if (isset($_GET['postId'])&&ctype_digit($_GET['postId'])) {

    # 13 ) que fait-on à cette ligne ? | on convertit la variable get en int (entier)
    # et on la stocke dans la variable $idpost
    $idpost = (int) $_GET['postId'];

    # 14 ) que récupère-t-on et de quel fichier provient cette fonction ?
    # | on passe en argument la connexion PDO et la variable id traitée au dessus
    # pour récupérer un post si il existe (0 ou 1 article récupéré)
    $recupPost = postOneById($connectPDO,$idpost);

    # 15 ) que reçoit'on en cas d'erreur, et que fait-on ensuite ? | si on reçoit un
    # booléen, on a pas trouvé l'article, on affiche le message d'erreur stocké dans $error
    # depuis la vue chargée : publicView/404View.php
    if(is_bool($recupPost)){
        // suite
        $error = "Cet article n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

        // 16 ) qu'appelle-t-on à cette ligne. | on appelle la page du détail avec une erreur
        # bloquante qui arrête le code
        require_once('../view/publicView/detailView.php');
}

// 17 ) cette condition vérifie quoi ? | si on a une catégorId en get est que le type est
# string dans lequel il n'y a qu'un entier numérique
}elseif(isset($_GET['categoryId'])&&ctype_digit($_GET['categoryId'])){   
    
    $id = (int) $_GET['categoryId'];

    $recupcateg=recupCategoryById($connectPDO,$id);

    // 18 )si on récupère quel type de valeur, on fait quoi ? | Si la valeur
    # est un booléen, on envoie la variable $error à la vue 404View
    if(is_bool($recupcateg)){
        // suite
        $error = "Cette catégorie n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";

    }else{
    # 19 ) que récupère-t-on et de quel fichier provient cette fonction ? 
    #   On récupère un tableau avec tous les articles appartenant à une
    # catégorie sélectionnée par id 
        $recupAllPost = postByCategoryId($connectPDO, $id);

        # 20 ) que fait-on ici ? | on compte le nombre de poste dans
        # la catégorie choisie (si il y en a, 0 est une option possible)

        $nbPost = count($recupAllPost);

        # 21 ) que fait-on ici ? | On ne charge qu'une fois la vue
        # de la catégorie (view/publicView/publicCategorieView.php)
        include_once("../view/publicView/publicCategorieView.php");
}

# 22) cette condition vérifie quoi ? | On vérifie l'existance de la variable get 'userId'
# et ne contient que des entiers positifs (dans un string)
}elseif(isset($_GET['userId'])&&ctype_digit($_GET['userId'])){ 

    # 23 ) qu'essaye t'on de récupérer ici, et de quel fichier provient cette fonction ?
    # On convertit en integer 'userId' dans la variable $iduser
    $iduser = (int) $_GET['userId'];
    # On déclare la variable $user dans laquelle on essaye de récupérer un utilisateur
    # par son id (0 ou 1)
    $user = getOneUserById($connectPDO,$iduser);

    # 24 ) si on récupère quel type de valeur, on fait quoi ?
    # | Si la variable $user est un booléen (false), on appel l'erreur 404
    if(is_bool($user)){
        // suite
        $error = "Cet utilisateur n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
    }else{
        # 25 ) que récupère-t-on et de quel fichier provient cette fonction ?
        # On essaie de récupérer les posts d'un utilisateur avec la fonction
        # se trouvant dans model/PostModel.php
        $recupAllPost = postByUserId($connectPDO,$iduser);

        # 26 ) que fait-on ici ? | on compte le nombre de post par user
        $nbPost = count($recupAllPost);

        # 27 ) que fait-on ici ? | appel de la vue
        include_once "../view/publicView/publicUserView.php";
    }

// sinon si on veut se connecter
}elseif(isset($_GET['connect'])){ 

    // si la personne a envoyé le formulaire
    if(isset($_POST['username'],$_POST['userpwd'])){
        // on essaye de connecter l'utilisateur avec 3 arguments : la connexion
        // le username et le mot de passe
        $connect = connectUserByUsername($connectPDO,
                                $_POST['username'],
                                $_POST['userpwd']
                            );
        // # 28 ) que reçoit-on en cas d'erreur, et que fait-on ensuite ?
        # si $connect est un string, on reste sur la page car erreur
        # on récupère le message d'erreur
        if(is_string($connect)) {
            $message = $connect;
        // #29) sinon, que fait-on ? Sinon on fait une redirection
        # vers la page d'accueil, le exit() est une bonne pratique
        # pour être certain que le serveur ne lise plus cette page
        # après (ou même avant)
        }else{
            header("Location: ./");
            exit();
        }
    }

    # 30 ) que fait-on ici ? | on charge la vue
    include "../view/publicView/connectView.php";

# 31 ) sinon, où sommes nous ? | Nous sommes sur la page d'accueil
}else{
    # homepage's datas from MODEL
    $recupAllPost = postHomepageAll($connectPDO);

    # Post count
    $nbPost = count($recupAllPost);


    # homepage's view from VIEW
    require "../view/publicView/publicHomepageView.php";
}