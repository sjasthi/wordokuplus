<?php
session_start();
$countFiles = count($_FILES['files']['name']);


$dir = "uploads/" . session_id();
$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($it,
             RecursiveIteratorIterator::CHILD_FIRST);
foreach($files as $file) {
    if ($file->isDir()){
        rmdir($file->getRealPath());
    } else {
        unlink($file->getRealPath());
    }
}
//rmdir($dir);



rmdir("uploads/" . session_id());





mkdir("uploads/" . session_id(), 0777);
$upload_location = "uploads/" . session_id() . "/";

$files = glob("uploads/" . session_id());
foreach($files as $file){
  if(is_file($file)) {
    unlink($file); 
  }
}

$count = 0;
for($i=0; $i<$countFiles; $i++){
	$fileName = $_FILES['files']['name'][$i];
	$path = $upload_location.$fileName;
	$file_extension = pathinfo($path, PATHINFO_EXTENSION);
	$file_extension = strtolower($file_extension);
	$valid_ext = array("png","jpeg", "jpg", "gif");
	if(in_array($file_extension, $valid_ext)){
		if(move_uploaded_file($_FILES['files']['tmp_name'][$i],$path)){
			$count++;
		}
	}
}
echo $count;
exit;
?>
