<?php

class PostsModel extends BaseModel
{
    
    public function index($title,$content){
        $statement = self::$db->prepare("UPDATE `posts` ".
            "SET `title`= ?,`content`= ? ".
            "WHERE id = ? ");
        
        $statement->bind_param("ss",$title,$content);
        $statement->execute();
        
        return $statement;
    }
    public function getPostById(int $id){
        $query = "SELECT title,content,author_id, date_created, username FROM posts LEFT JOIN users ON posts.author_id = users.id WHERE posts.id = ?";
        $statement = self::$db->prepare($query);

        $statement->bind_param("i",$id);
        $statement->execute();
        
        return $statement;

    }
    
    public function create(){
        $title = $_POST[FORM_POST_TITLE];
        $content = $_POST[FORM_POST_CONTENT];
        
        $statement = self::$db->prepare("INSERT INTO `posts` (`title`, `content`,`author_id`) VALUES (?,?,?)");
        $statement->bind_param("ssi", $title, $content,$_SESSION['userId']);
        $statement->execute();
        
        return $statement;        
    }

    public function comment($postId){
        $content = $_POST['content'];
        $statement = self::$db->prepare("INSERT INTO `comments` (`content`, `post_id`,`author_id`) VALUES (?,?,?)");
        $statement->bind_param("sii", $content, $postId, $_SESSION['userId']);
        $statement->execute();

        return $statement;
    }

    public function getComments($postId){
        $statement = self::$db->prepare("SELECT content, date, username, author_id FROM comments LEFT JOIN users ON comments.author_id = users.id WHERE comments.post_id = ? ORDER BY `date` DESC");
        $statement->bind_param("i", $postId);
        $statement->execute();
        
        return $statement;
    }
}