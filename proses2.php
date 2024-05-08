<?php
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $filename       = basename($_FILES["fileToUpload"]["name"]);
    $filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);


    $save_path      = getcwd().'/dowloads'.'/'.$filename_no_ext.'.jpg';
    $save_shp       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.shp';
    $save_zip       = getcwd().'/dowloads'.'/'.$filename_no_ext.'.zip';

    $shp            = $filename.'.shp';
    $shx            = $filename.'.shx';
    $dbf            = $filename.'.dbf';
    $cpg            = $filename.'.cpg';


    // Inisialisasi array respons
    $response = array();

    // Periksa apakah file gambar atau bukan
    if(isset($_FILES["fileToUpload"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Batasi ukuran file
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $uploadOk = 0;
    }

    // Izinkan hanya format gambar tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }

    // Cek jika $uploadOk bernilai 0 oleh kesalahan apa pun
    if ($uploadOk == 0) {
        $response['status'] = 500;
        $response['message'] = "Terjadi kesalahan saat mengunggah gambar.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

            $py = exec("py python/proses1.py " . $target_file.' '.$save_path. ' ' .$filename .' '.$save_shp.' '.$shp.' '.$shx.' '.$dbf.' '.$cpg.' '.$save_zip);

            $cekFiles = '/dowloads'.'/'.$filename_no_ext.'.zip';

            if(!file_exists($cekFiles)){
                exec("py python/proses1.py " . $target_file.' '.$save_path. ' ' .$filename .' '.$save_shp.' '.$shp.' '.$shx.' '.$dbf.' '.$cpg.' '.$save_zip);
            }


            $response['status'] = 200;
            $response['message'] = "Gambar berhasil diunggah.";
            $response['cekFileZip'] = file_exists($cekFiles);

            // Jika perlu, Anda bisa menambahkan data tambahan di sini
        } else {
            $response['status'] = 500;
            $response['message'] = "Terjadi kesalahan saat mengunggah gambar.";
        }
    }

    // Mengirimkan respons sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
