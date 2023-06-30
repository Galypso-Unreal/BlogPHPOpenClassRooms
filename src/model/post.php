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

    $sql = "SELECT id,prenom,nom,email FROM b_utilisateur WHERE id=:id";

    $data = $db->prepare($sql);

    $data->execute(array(':id'=>$id));
    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $author = [
            'id'=>$row['id'],
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

function modifyPost($id,$title,$chapo,$content,$id_user){
    $database = new DatabaseConnection();
    $db = $database->getConnection();


    $sql = "UPDATE b_post SET titre=:titre,chapo=:chapo,contenu=:contenu,fk_utilisateur_id=:fk_utilisateur_id,modified_at=:modified_at WHERE id=:id";
    $insert = $db->prepare($sql);
    $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
    $datef = $date->format('Y-m-d H:i:s');

    $insert->bindParam(':titre', $title);
    $insert->bindParam(':chapo', $chapo);
    $insert->bindParam(':contenu', $content);
    $insert->bindParam(':fk_utilisateur_id', $id_user);
    $insert->bindParam(':modified_at', $datef);
    $insert->bindParam(':id', $id);

    $insert->execute();

    header('Location: http://blog.local/admin/posts');

}

function deletePost($id){
    if(isset($id) && $id >=0){
        $database = new DatabaseConnection();
        $db = $database->getConnection();

        $sql = "DELETE FROM b_post WHERE id=:id";

        $delete = $db->prepare($sql);
        $delete->execute(array(':id'=>$id));

        header('Location: http://blog.local/admin/posts');
    }
    
}

function getAdmins(){

    $database = new DatabaseConnection();
    $db = $database->getConnection();
    
    $sql = "SELECT u.id,u.nom,u.prenom,u.email FROM b_utilisateur u JOIN b_role r ON r.id = u.fk_role_id WHERE r.label = 'Administrateur'";

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