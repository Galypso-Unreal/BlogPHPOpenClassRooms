<?php

function confirmationPostAdd(){
    if(isset($_POST['title']) && isset($_POST['chapo']) && isset($_POST['content']) && isset($_POST['id_user'])){
        if(strlen($_POST['title']) > 80){
            return 0;
        }
        return 1;
    }
    return 0;
}

function addPost($title,$chapo,$content,$id_user){
    $database = new DatabaseConnection();
    $db = $database->getConnection();


    $sql = "INSERT INTO b_post (titre,chapo,contenu,fk_utilisateur_id) VALUES (:titre,:chapo,:contenu,:fk_utilisateur_id)";
    $insert = $db->prepare($sql);
    $insert->execute(array(
        ":titre"=>$title,
        ":chapo"=>$chapo,
        ":contenu"=>$content,
        ":fk_utilisateur_id"=>$id_user,
    ));

    header('Location: http://blog.local/admin/post/add');

}


function getPosts(){

    $database = new DatabaseConnection();
    $db = $database->getConnection();

    $sql = "SELECT * FROM b_post";

    $data = $db->prepare($sql);
    $data->execute();

    $posts = [];

    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $post = [
            'id'=>$row['id'],
            'title' => $row['titre'],
            'chapo' => $row['chapo'],
            'content' => $row['contenu'],
            'created_at' =>$row['created_at'],
            'modified_at'=>$row['modified_at'],
            'author'=>getAuthor($row['fk_utilisateur_id'])
        ];
        $posts[] = $post;
    }
    return $posts;
}

function getAuthor(int $id): Array{
    $database = new DatabaseConnection();
    $db = $database->getConnection();

    $sql = "SELECT prenom,nom,email FROM b_utilisateur WHERE id=:id";

    $data = $db->prepare($sql);

    $data->execute(array(':id'=>$id));
    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $author = [
            'firstname'=>$row['prenom'],
            'lastname'=>$row['nom'],
            'email'=>$row['email']
        ];
    }

    return $author;
}

function getPost(int $id){

    if (!isset($id) || $id <= 0) {
        header('Location: http://blog.local/');
    }

    $database = new DatabaseConnection();
    $db = $database->getConnection();

    $sql = "SELECT * FROM b_post WHERE id=:id";

    $data = $db->prepare($sql);
    $data->execute(array('id'=>$id));

    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $post = [
            'id'=>$row['id'],
            'title' => $row['titre'],
            'chapo' => $row['chapo'],
            'content' => $row['contenu'],
            'author'=>getAuthor($row['fk_utilisateur_id']),
            'modified_at'=>$row['modified_at']
        ];
    }
    
    return $post;
}

function getAdmins(){

    $database = new DatabaseConnection();
    $db = $database->getConnection();
    
    $sql = "SELECT id,nom,prenom,email FROM b_utilisateur WHERE fk_role_id = 1";

    $data = $db->prepare($sql);

    $data->execute();

    $admins = [];

    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $admin = [
            'id'=>$row['id'],
            'lastname' => $row['nom'],
            'firstname' => $row['prenom'],
            'email' => $row['email'],
        ];
        $admins[] = $admin;
    }
    return $admins;

}