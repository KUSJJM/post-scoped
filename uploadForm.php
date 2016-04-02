<?php

use frontRow\UploadFile;

//$max = 1024 * 1024 * 30;

//$max = 1024 * 50;

//THIS WILL ALREADY HAVE BEEN DONE IN MODULE.PHP ETC AND DOES NOT NEED TO BE INCLUDED.
session_start();
//DON'T INCLUDE SESSION_START(), mkay?

require_once '_includes/frontRow/UploadFile.php';

if(!isset($_SESSION['maxfiles'])){
    $_SESSION['maxfiles'] = ini_get('max_file_uploads');
    $_SESSION['postmax'] = UploadFile::convertToBytes(ini_get('post_max_size'));
    $_SESSION['displaymax'] = UploadFile::convertFromBytes($_SESSION['postmax']);
}

$max = 32000000;
$result = [];

$moduleID = 'CI5100/';
$destination = __DIR__ . '/_uploads/' . $moduleID;

if(!is_dir($destination)) {
    mkdir($destination, 0755);
}

if(isset($_POST['upload'])) {
    
    
//    $destination = __DIR__ . '/_uploads/';
    
    try {
        $upload = new UploadFile($destination);
//        $upload->setMaxSize($max);
        $upload->allowAllTypes();
        $upload->upload();
        $result = $upload->getMessages();
    } catch (Exception $e) {
        $result[] = $e->getMessage();
    }
}

$error = error_get_last();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Upload Files</title>
    </head>
    <body>
        <h1>Upload files with PHP</h1>
        <?php if($result || $error) : ?>
        <ul class="result">
            <?php 
            
                if($error) {
                    echo "<li>{error['message']}</li>";
                }
            
            if($result) {
            foreach($result as $message) : ?>
                <li><?= $message ?></li>
            <?php endforeach ?>
            <?php } ?>
        </ul>
        <?php endif ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <p>
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= $max ?>">
                <label for="filename">Select File</label>
                <input type="file" name="filename[]" id="filename" multiple
                       data-maxfiles="<?= $_SESSION['maxfiles'] ?>"
                       data-postmax="<?= $_SESSION['postmax'] ?>"
                       data-displaymax="<?= $_SESSION['displaxmax'] ?>">
            </p>
            <p>
                <input type="submit" name="upload" value="Upload File">
            </p>
            <ul>
                <li>Up to <?php echo $_SESSION['maxfiles'];?> files can be uploaded simultaneously.</li>
                <li>Each file should be no more than <?php echo UploadFile::convertFromBytes($max);?>.</li>
                <li>Combined total should not exceed <?php echo $_SESSION ['displaymax'];?>.</li>
            </ul>
        </form>
        
        
        <?php $files = scandir($destination); ?>
        <select>
            <?php foreach($files as $file) : ?>
                <option value="<?= $file ?>"><?= $file ?></option>
            <?php endforeach ?>
        </select>
        <ul>
            <?php foreach($files as $file) : ?>
            <li><a href="<?php echo '/_uploads/' . $file ?>" target="_blank"><?= $file ?></a></li>
            <?php endforeach ?>
        </ul>
        
        <script src="_js/checkMultiple.js"></script>
    </body>
</html>