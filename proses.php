<?php

// Pastikan file gambar telah diunggah

$target_dir     = "uploads/";
$target_file    = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$filename       = basename($_FILES["fileToUpload"]["name"]);

$filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);


$save_path      = getcwd().'/dowloads'.'/'.$filename_no_ext.'.jpg';
$save_shp       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.shp';
$save_zip       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.zip';

$shp            = $filename.'.shp';
$shx            = $filename.'.shx';
$dbf            = $filename.'.dbf';
$cpg            = $filename.'.cpg';

$response = array();

if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
    if (!file_exists($save_zip)) {
        exec("py python/proses1.py " . $target_file.' '.$save_path. ' ' .$filename .' '.$save_shp.' '.$shp.' '.$shx.' '.$dbf.' '.$cpg.' '.$save_zip);
    }
    $response['status'] = 200;
    $response['message'] = "Gambar berhasil diproses.";
    $response['imageUpload'] = $target_file;
    $response['imageProcess'] = 'dowloads'.'/'.$filename_no_ext.'.jpg';
    $response['buttonDownload']  = 'dowloads/'.$filename_no_ext.'.zip';

}else {
    $response['status'] = 500;
    $response['message'] = "Terjadi kesalahan saat mengunggah gambar.";
}

$response['files'] = file_exists($save_zip);

// $py = exec("py python/proses1.py " . $target_file.' '.$save_path. ' ' .$filename .' '.$save_shp.' '.$shp.' '.$shx.' '.$dbf.' '.$cpg.' '.$save_zip);

// $data = [
//     'Path Gambar' => $target_dir,
//     'Path Download' => $save_path,
//     'Filename' => $filename,
//     'File SHP' => $save_shp
// ];
// print_r($data);
header('Content-Type: application/json');
echo json_encode($response);

?>
