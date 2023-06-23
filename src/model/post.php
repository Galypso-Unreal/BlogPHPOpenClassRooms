<?php



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
            'modified_at'=>$row['modified_at']
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

    $author = $data->fetch(PDO::FETCH_ASSOC);

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