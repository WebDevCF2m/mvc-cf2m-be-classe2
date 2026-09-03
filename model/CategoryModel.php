<?php
# 50 ) que fait-on ici ? | On sélectionne l'id et le title de 
# toutes les catégories ordonnées par id ascendant, on
# reçoit un tableau d'office (vide si pas de catégories)
function getAllCategoryMenu(PDO $db): array {
    $sql ="SELECT id, title FROM category ORDER BY id ASC";
    try{
        $query=$db->query($sql);
    }catch(Exception $e){
        die($e->getMessage());
    }
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

# 51 ) que fait-on ici ? | On récupère tout les champs de category
# par id dans un tableau associatif, sinon le fetch renvoie false
function recupCategoryById(PDO $db,int $id):array|bool
{
    $recup = "SELECT * FROM category where id=?";
    $prepare = $db -> prepare($recup);
    try{
        $prepare->execute([$id]);
    }catch(Exception $e){
        die($e->getMessage());
    }
    $bp = $prepare->fetch(PDO::FETCH_ASSOC);
    $prepare->closeCursor();
    return $bp;
}

