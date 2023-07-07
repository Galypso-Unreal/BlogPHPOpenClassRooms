<?php
function confirmationComment(){
    if(isset($_GET['id']) && isset($_POST['comment'])){
        return 1;
    }
    else{
        return 0;
    }
}
function addComment(string $message){
    $database = new DatabaseConnection();
    $db = $database->getConnection();
    
    $user = 1;
    $post = $_GET['id'];


    $sql = "INSERT INTO b_commentaire (content,fk_utilisateur_id,fk_post_id) VALUES (:content,:fk_utilisateur_id,:fk_post_id)";
    $insert = $db->prepare($sql);

    $insert->bindParam(':content', $message);
    $insert->bindParam(':fk_utilisateur_id', $user);
    $insert->bindParam(':fk_post_id', $post);

    $insert->execute();

    header('Location: http://blog.local/actualite/'.$post);
    
}

function getComments($id){

    $database = new DatabaseConnection();
    $db = $database->getConnection();

    $sql = "SELECT id,content,fk_utilisateur_id FROM b_commentaire WHERE fk_post_id = :id AND est_valide = '1'";
    

    $data = $db->prepare($sql);

    $data->bindParam(':id',$id);

    $data->execute();

    $comments = [];

    while(($row = $data->fetch(PDO::FETCH_ASSOC))){
        $comment = [
            'id'=>$row['id'],
            'content' => $row['content'],
            'user' => getAuthor($row['fk_utilisateur_id']),
        ];
        $comments[] = $comment;
    }
    return $comments;

}