<?php

use frontRow\Post;

require_once '_includes/frontRow/Post.php';

try {
    $dsn = 'mysql:host=localhost;dbname=lmsTesting';
    $db = new PDO($dsn, 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    $error = $e->getMessage();
}

$moduleID = 'CI5100/';
$destination = __DIR__ . '/_uploads/' . $moduleID;

if(!is_dir($destination)) {
    mkdir($destination, 0755);
}

if(isset($_POST['upload']) && isset($_POST['postTitle'])) {
    $sql = 'INSERT INTO testPost (title, content, commentsAllowed, dateTimePosted)
            VALUES (:title, :content, :commentsAllowed, now())';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $_POST['postTitle']);
    $stmt->bindParam(':content', $_POST['postContent']);
    if(isset($_POST['commentsAllowed'])) {
        $commentsAllowed = 1;
        $stmt->bindParam(':commentsAllowed', $commentsAllowed);
    } else {
        $commentsAllowed = 0;
        $stmt->bindParam(':commentsAllowed', $commentsAllowed);
    }
    $stmt->execute();
    
    if(isset($_POST['fileChoice'])) {
        $sql = 'SELECT LAST_INSERT_ID();';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $lastID = $stmt->fetchColumn();
        
        print_r($lastID);
        
        $postFiles = $_POST['fileChoice'];
        
        foreach($postFiles as $file) {
        $sql = 'INSERT INTO testPostFile (postID, fileName)
                VALUES (:postID, :fileName)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':postID', $lastID);
        $stmt->bindParam(':fileName', $file);
        $stmt->execute();
        }
        print_r($_POST['fileChoice']);
    }
    
//    $sql = 'INSERT INTO testMain (testName)
//            VALUES (:testName)';
//    $stmt = $db->prepare($sql);
//    $stmt->bindParam(':testName', $_POST['postTitle']);
//    $stmt->execute();
//    
//    if(isset($_POST['secondTitle'])) {
//        $sql = 'SELECT LAST_INSERT_ID();';
//        $stmt = $db->prepare($sql);
//        $stmt->execute();
//        $lastID = $stmt->fetchColumn();
//        
//        print_r($lastID);
//        
//        $secondaryTitles = $_POST['secondTitle'];
//        
//        foreach($secondaryTitles as $secondTitle) {
//        $sql = 'INSERT INTO testSecondary (mainTestID, secondaryName)
//                VALUES (:testMainID, :secondaryName)';
//        $stmt = $db->prepare($sql);
//        $stmt->bindParam(':testMainID', $lastID);
//        $stmt->bindParam(':secondaryName', $secondTitle);
//        $stmt->execute();
//        }
//        print_r($_POST['secondtitle']);
//    }
    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Test multi insert</title>
    </head>
    <body>
        <h1>Test Multiple Insert referencing initial insert ID.</h1>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <p>Enter Post Title</p>
            <input type="text" name="postTitle">
            <p>Enter Post Content</p>
            <input type="text" name="postContent">
            <p>Comments allowed:</p>
            <input type="checkbox" name="commentsAllowed">
            <!-- Comments on/off can be dealt with later -->
<!--
            <input type="text" name="secondTitle[]">
            <input type="text" name="secondTitle[]">
-->
            <?php $files = scandir($destination); ?>
            <p>Choose a file to upload:</p>
            <select name="fileChoice[]">
                <?php foreach($files as $file) : ?>
                    <option value="<?= $file ?>"><?= $file ?></option>
                <?php endforeach ?>
            </select>
            <p>Choose another file to upload:</p>
            <select name="fileChoice[]">
                <?php foreach($files as $file) : ?>
                    <option value="<?= $file ?>"><?= $file ?></option>
                <?php endforeach ?>
            </select><br>
            <input type="submit" name="upload">
        </form>
        
        
        
        <ul>
            <?php foreach($files as $file) : ?>
            <li><a href="<?php echo '/_uploads/' . $file ?>" target="_blank"><?= $file ?></a></li>
            <?php endforeach ?>
        </ul>
        
        <?php
            //Select posts
            $sql = 'SELECT * FROM testPost';
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
        ?>       
        <?php foreach($posts as $post) : ?>
        <section>
            <h3><?= $post->title ?></h3>
            <p><?= $post->content ?></p>
            <?php 
                $postID = $post->postID;
                $sql = 'SELECT fileName FROM testPostFile WHERE postID = :postID';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':postID', $postID);
                $stmt->execute();
                $postFileArr = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                
                foreach($postFileArr as $linkedFile){
                    echo '<p><a href="/_uploads/' . $moduleID . $linkedFile . '">' . $linkedFile . '</a></p>';
                }
                
                if($post->commentsAllowed){
                    echo '<p>Comments allowed!</p>';
                } elseif(!$post->commentsAllowed) {
                    echo '<p>No comments allowed!</p>';
                } else {
                    echo '<p>Something went wrong.</p>';
                }
            ?>
        </section>    
        <?php endforeach ?>
        <pre>
            <?php print_r($posts); ?>
        </pre>
        
    </body>
</html>