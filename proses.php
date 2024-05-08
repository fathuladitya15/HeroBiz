<?php

// Pastikan file gambar telah diunggah

$target_dir     = "uploads/";
$target_file    = $target_dir . basename($_FILES["image"]["name"]);

$filename       = basename($_FILES["image"]["name"]);

$filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);


$save_path      = getcwd().'/dowloads'.'/'.$filename_no_ext.'.jpg';
$save_shp       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.shp';
$save_zip       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.zip';

$shp            = $filename.'.shp';
$shx            = $filename.'.shx';
$dbf            = $filename.'.dbf';
$cpg            = $filename.'.cpg';

$py = exec("py python/proses1.py " . $target_file.' '.$save_path. ' ' .$filename .' '.$save_shp.' '.$shp.' '.$shx.' '.$dbf.' '.$cpg.' '.$save_zip);

move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

$data = [
    'Path Gambar' => $target_dir,
    'Path Download' => $save_path,
    'Filename' => $filename,
    'File SHP' => $save_shp
];
print_r($data);

?>
