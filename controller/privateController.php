<?php

# 32 ) que fait-on ici ? | si la variable get 'disconnect'
# existe on déconnecte l'utilisateur avec la fonction deconnect()
# du fichier model/UserModel.php puis redirection
if (isset($_GET['disconnect'])) {
    // si déconnexion renvoie true
    if (deconnect()) {
        // redirection
        header("Location: ./");
        exit();
    }

// 33 ) à quoi pourrait servir ce bloc de code ? | sinon si 
# si il existe les variables get  postVisible et id et qu'elles
# sont de type numérique, on les convertit
# en entier avec (int) 
}elseif(isset($_GET['postVisible'],$_GET['id'])
    &&ctype_digit($_GET['postVisible'])
    &&ctype_digit($_GET['id'])
    ){
    $postId = (int) $_GET['id'];
    $postVisible = (int) $_GET['postVisible'];

    // 34 ) que fait-on ici ? | Si on cliqué sur activer ou 
    # désactiver, on va changer la valeur par son contraire
    # on fait dans les 2 cas on fait une redirection
    if (postAdminUpdateVisible($connectPDO, $postId, $postVisible)) {
        header("Location: ./?m=L'article dont l'id est $postId a été modifié");
        exit();
    } else {
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

// 34 ) que veut on faire ici ?  | sinon si la variable get
#  createPost existe
}elseif(isset($_GET['createPost'])){

    // 35) si on a envoyé ... quoi ? | Si on a rempli le formulaire
    if(isset($_POST['title'],$_POST['content'],$_POST['user_id'])){
        // 36 ) que fait-on ici ? | conversion en entier
        $UserId = (int) $_POST['user_id']; // si erreur => 0
        // on retire les espaces, les tags, puis on convertit les 
        # caractères spéciaux dont les ' et " en entités html 
        $postTitle = htmlspecialchars(strip_tags(trim($_POST['title'])),ENT_QUOTES);
        $postContent = htmlspecialchars(strip_tags(trim($_POST['content'])),ENT_QUOTES);
        // ternaire ! si tableau les valeurs et clefs ne sont pas protégée contre une manipulation externe (injection etc...)
        $idCateg = (isset($_POST['category_id'])&&is_array($_POST['category_id']))? $_POST['category_id'] : [];

    if(!empty($UserId)&&!empty($postTitle)&&!empty($postContent)) {
        //  Pouvoir insérer un article AVEC ses catégories
        $insert = postAdminInsert($connectPDO, $UserId, $postTitle, $postContent, $idCateg);
        if($insert===true){
            $message = "Article inséré dans la DB";
        }
    }
    }

    // 37 )Appel des catégories pour . | Pour pouvoir créer
    # les checkboxs des catégories pour le nouvel article
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 38 ) On appel qui? | On récupère tous les utilisateurs
    # pour pouvoir sélectionner l'auteur du poste
    $userChoice = getAllUsers($connectPDO);

    // 39) que fait-on ici ? | On appel la vue privée
    # d'insertion avec le formulaire
    include "../view/privateView/privateInsertView.php";

// 40 ) que fait-on ici ? | si il existe le updatePost en get et 
# si il est bien composé que de numérique 
}elseif(isset($_GET['updatePost'])&&ctype_digit($_GET['updatePost'])){

    // si on a envoyé le formulaire de modification
    if(isset($_POST['title'])){
        // pas de vérification des variables $_POST au niveau du contrôleur !!! -> TOUTES LES Vérification doivent se trouver dans la fonction ! 
        $post = postAdminUpdate($connectPDO,$_POST); 
        // 41 ) quel type de retour pour avoir une erreur
        if(is_string($post)){
            // affichage de l'erreur
            $message = $post;
        }
        // 42 ) quel type de retour pour avoir un succès
        # si la variable $post est strictement true
        if($post===true){
            $message = "L'article a bien été modifié<script>
            setTimeout(\"location.href = './';\", 2000);
             </script>";
        }
    }

    $idUpdatePost = (int) $_GET['updatePost'];

    # 43 ) que récupère t'on | On récupère le post qu'on 
    # veut modifier via son id
    $recupPost = postOneById($connectPDO,$idUpdatePost);

    # 44 ) que type de valeur peut-on récupérer ici, et que 
    # fait-on ensuite ? | si la valeur de recupPost est un 
    # booléen (dans notre cas false envoyé par un fetch vide)
    # On appel l'erreur 404 (attention utilisation d'une vue)
    # Frontend qui nécessite le menu
    
    if(is_bool($recupPost)){
        # récupération du menu pour l'erreur 404
        $recupMenu = getAllCategoryMenu($connectPDO);
        // création de l'erreur pour la 404
        $error = "Cet article n'existe plus";
        // appel de la vue 404
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

    // 45) on appel les ... | catégories pour l'update
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 46 ) on appel les ... | users pour l'update
    $userChoice = getAllUsers($connectPDO);

    // 47 ) on appel la ... | la vue de la page privée pour l'update
    include "../view/privateView/privateUpdateView.php";
}

// 48 ) que fait-on ici ? | Si on trouve la variable get
# deletePost et qu'elle contient que des numériques
}elseif(isset($_GET['deletePost'])&&ctype_digit($_GET['deletePost'])){

    $postId = (int) $_GET['deletePost'];

    if(postAdminDeleteById($connectPDO,$postId)){
        header("Location: ./?m=L'article dont l'id est $postId a été supprimé");
        exit();
    }else{
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

    
// 49) quel est cette page  | Sinon on est sur la page
# d'accueil de l'administration
}else{
    // appel due la méthode (fonction) modèle PostModel pour afficher tous les articles SANS restrictions
    $postAll = postAdminHomepageAll($connectPDO);
    // on compte le nombre d'articles
    $postCount = count($postAll);
    // appel de la vue de l'accueil
    include "../view/privateView/privateHomepageView.php";
}