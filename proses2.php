<?php
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

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
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }

    // Cek jika $uploadOk bernilai 0 oleh kesalahan apa pun
    if ($uploadOk == 0) {
        $response['status'] = 500;
        $response['message'] = "Terjadi kesalahan saat mengunggah gambar.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $response['status'] = 200;
            $response['message'] = "Gambar berhasil diunggah.";
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
