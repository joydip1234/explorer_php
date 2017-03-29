<?php
/**
 * Created by PhpStorm.
 * User: Sudipta
 * Date: 3/28/2017
 * Time: 11:03 AM
 */
ini_set('post_max_size', '250M');
ini_set('upload_max_filesize', '200M');
$count = 0;
//$wr = fopen('files/new.txt', 'w');
$toshow = true;
$uploaded = [];
if(!isset($_COOKIE["len"])){
    setcookie("len",0, time() + (86400 * 30), "/");
}
if (!empty($_FILES["file"] )) {
    $uploaded = null;
    if($_POST['ajax'] ==true){
	    $uploaded = array();
	    foreach ($_FILES["file"]["name"] as $key => $name) {
	        if ($_FILES['file']['error'][$key] == 0 && move_uploaded_file($_FILES['file']['tmp_name'][$key], "files/{$name}")) {
	            if(!in_array($name,$_COOKIE)){
	                $count++;
	                setcookie(crc32($name), $name, time() + (86400 * 30), "/");
	                $uploaded[$key] = $name;
	                //$toWrite = " \r\n[" . $key . "] " . $uploaded[$key] . "      --size " . round($_FILES['file']['size'][$key] / 1024) . "kB \r\n";
	                //fwrite($wr, $toWrite);
	            }else{
	                $uploaded[$key] = "already_here";
	            }
	        }

	        setcookie("len",$count, time() + (86400 * 30), "/");	
	    }
    
    }
    //fclose($wr);
    returnAjax($uploaded);
}

if(isset($_POST['deleteFiles'])){
    array_map('unlink', glob("./files/*.*"));
}



function returnAjax($uploaded)
{
    if (!empty($_POST['ajax'])) {
        die(json_encode($uploaded));
    }
}
?>

<html>
<meta name="viewport" charset="utf-8">
<link href="s.css" rel="stylesheet">
<head><title>File Manager</title></head>
<body>

<!--?php
echo "<div style='background-color: #7986cb;color: #EEE;padding: 10px;text-transform: uppercase;font-size: 0.95em;'>total uploaded files = <span id='count_holder'>";
$c = 0;
$files = glob('./files/*');if(!empty($files)){foreach($files as $file){if(is_file($file))$c++;}}

echo $c."</span></div>";
?-->
<div id="progress_bar">

</div>
<div id="uploaded">
    <span style="color: #fff;display: block;text-transform: uppercase;font-size: 0.7em;width: 100%;background-color: #7986cb;padding: 5px;box-sizing: border-box;">Uploaded Images</span>
    <?php
    /*$allowedExts = array("jpeg","jpg","svg","png","bmp","gif");
    if (isset($_COOKIE)) {
        foreach ($_COOKIE as $key => $value) {
            $temp = explode(".",$value);
            if($key != "len" && in_array(end($temp), $allowedExts)){
                echo "<img class='shownfile' src='./files/" . $value . "' />";
            }
        }
        $toshow = true;
    }*/
    $files = glob('./files/*'); // get all file names
    if(!empty($files) || $toshow == true){
        $allowedExts = ["jpeg","jpg","svg","png","bmp","gif"];
        foreach($files as $file){
            if(is_file($file))
                $words  = explode("/",$file);
                $temp = explode(".",$words[2]);
                //print_r($file);
                //print_r($temp);
                if(in_array($temp[1],$allowedExts)){
                    echo "<img class='shownfile' src='".$file."'/>";
                }
        }
        $toshow = false;
    }
    ?>
</div>
<div style="background-color: #7986cb;color: #eee;width: 100%;padding: 15px;box-sizing: border-box">Files in Directory [
    <?php
    echo "<span id='count_holder'>";
    $c = 0;
    $files = glob('./files/*');if(!empty($files)){foreach($files as $file){if(is_file($file))$c++;}}
    echo $c."</span>"
    ?>
] </div>
<div id="folder">
    <?php
    $files = glob('./files/*'); // get all file names
	$index = 1;
    if(!empty($files) || $toshow == true){
        foreach($files as $file){ // iterate files
            if(is_file($file))
                $words  = explode("/",$file);
            echo "<div class='file_cont'><a class='file_ls' href='".$file."'>[".$index."] ".$words[2]."</a><span class='rm'> &#10005; </span></div>";
			$index ++;
        }
		
        $tosshow = false;
    }else{
        echo "<p style='padding: 20px;text-align: center;width: 100%;' id='noFileToast'>No File in Directory</p>";
    }
    ?>
</div>

    <div id="fu">
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file[]" value="" multiple="multiple" id="fileIn">
            <input type="submit" value="upload" id="upload" style="margin-top: 5px;">
            <!--<span style="clear: both;display: block;"></span>-->
        </form>

        <form>
            <input type="button" value="clear cookies" name="clrck" id="clrck">
            <input type="button" value="Delete Files" name="filedlt" id="filedlt">
            <input type="button" value="Clear Log"  id="clearLog">
        </form>

    </div>


<div id="log"><span id="log_header" style="padding: 10px;text-align: center;display: none;color: #eee;">Added Files</span> </div><br><br><br><br>
<script src="main.js" type="text/javascript"></script>
<!--<script src="jq.js" type="text/javascript"></script>-->
</body>
</html>
