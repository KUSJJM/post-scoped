<?php

//use frontRow;
use frontRow\Post;
use frontRow\Link;
use frontRow\Comment;

require_once '_includes/frontRow/Post.php';
require_once '_includes/frontRow/Link.php';
require_once '_includes/frontRow/Comment.php';

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

    $lastID;

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

    if(isset($_POST['linkName']) && isset($_POST['linkHref'])){
        $linkNames = $_POST['linkName'];
        $linkHrefs = $_POST['linkHref'];

        $linkNumber = count($linkNames);

        for($i = 0; $i < $linkNumber; $i++) {
            $sql = 'INSERT INTO testPostLink (postID, linkName, linkHref)
                    VALUES (:postID, :linkName, :linkHref)';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':postID', $lastID);
            $stmt->bindParam(':linkName', $linkNames[$i]);
            $stmt->bindParam(':linkHref', $linkHrefs[$i]);
            $stmt->execute();
        }
    }
}

if(isset($_POST['postComment'])) {
    $sql = 'INSERT INTO testPostComment (postID, commentText, dateTimeCommented)
            VALUES (:postID, :commentText, now())';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':postID', $_POST['postID']);
    $stmt->bindParam(':commentText', $_POST['commentText']);
    $stmt->execute();
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
            <?php $directoryContents = scandir($destination);

            $files = array_diff($directoryContents, array('.', '..')); ?>
            <p>Add files to post or remove files:</p>
            <button id="addFileChoice">Add File</button>
            <button id="removeFileChoice">Remove File</button>
            <div id="file-choice-section">
                <select class="fileChoice" name="fileChoice[]">
                    <?php foreach($files as $file) : ?>
                    <?php if($file != "." || $file != "..") : ?>
                        <option value="<?= $file ?>"><?= $file ?></option>
                    <?php endif ?>

                    <?php if($file == "."){
                            echo 'It equals .';
                        }
                    ?>
                    <?php endforeach ?>
                </select>
            </div>
            <p>Add links to post or remove links:</p>
            <button id="addLinkChoice">Add Link</button>
            <button id="removeLinkChoice">Remove Link</button>
            <div id="link-choice"></div>

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

            ?>
            <p>Files:</p>
            <?php
                foreach($postFileArr as $linkedFile){
                    echo '<p><a href="/_uploads/' . $moduleID . $linkedFile . '">' . $linkedFile . '</a></p>';
                }

            ?>
            <p>Links:</p>
            <?php
                $sql = 'SELECT * FROM testPostLink WHERE postID = :postID';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':postID', $postID);
                $stmt->execute();
                $links = $stmt->fetchAll(PDO::FETCH_CLASS, 'Link');

                foreach($links as $link){
                    echo '<p><a href="' . $link->linkHref . '">' . $link->linkName . '</a></p>';
                }
            ?>
            <?php
                if($post->commentsAllowed){
                    echo '<p>Comments allowed!</p>';
                    $sql = 'SELECT * FROM testPostComment WHERE postID = :postID';
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':postID', $postID);
                    $stmt->execute();
                    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, 'Comment');
                    foreach($comments as $comment) {
                        echo '<p>' . $comment->commentText . '</p>';
                    }
            ?>
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                <input type="hidden" name="postID" value="<?= $post->postID ?>">
                <input type="text" name="commentText">
                <input type="submit" name="postComment">
            </form>
            <?php
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
        <script src="_js/postOptions.js"></script>
    </body>
</html>
