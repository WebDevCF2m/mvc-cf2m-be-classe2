<?php

// exemple d'une classe, son nom doit correspondre au nom du fichier
class CommentModel{
    // Attributs -> variables
    public int $id=1; // public
    private string $text="coucou";// private

    // getter (récupérer une information) méthodes
    public function getText(){
        return $this->text;
    }
    // setter
    public function setText(string $message){
        if(strlen($message)>3){
            $this->text = $message;

        }else{
            echo "texte trop court !";
        }
    }
}
