<?php

//Creando el servicio para las imágenes

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AthleteManager {
    public function load (UploadedFile $file, string $pathFile) {
        $fileName = uniqid().'.'.$file -> guessClientExtension();
        $file -> move($pathFile, $fileName);

        return $fileName;
    }

}

?>