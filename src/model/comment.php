<?php 

namespace Application\Model\Comment;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;

class Comment{
    public int $id;
    public string $comment;
    public bool $is_valid;
    public string $deleted_at;
    public int $fk_user_id;
    public int $fk_post_id;
}

class CommentRepository{

    public DatabaseConnection $connection;

    function getAllComments(){

        $db = $this->connection->getConnection();
    
        $sql = "SELECT * FROM b_comment WHERE deleted_at IS NULL ORDER BY is_valid";
        
    
        $data = $db->prepare($sql);
    
        $data->execute();
    
        $comments = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $comment = [
                'id'=>$row['id'],
                'content' => $row['content'],
                'valid'=> $row['est_valide'],
                // 'user' => getAuthor($row['fk_utilisateur_id']),
            ];
            $comments[] = $comment;
        }
        return $comments;
    
    }

}

