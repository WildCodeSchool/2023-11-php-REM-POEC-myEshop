<?php

namespace App\Service;

use App\Service\SessionManager;

class Utils
{
    protected $session;
    public function __construct()
    {
        $this->session = new SessionManager();
    }

    public function uploadPhotoProfil(array $file): string
    {
        $uploadDir = 'upload/profil';
        $authorizedExtensions = ['jpeg', 'jpg', 'png'];
        $maxFileSize = 1000000;

        $photoProfil = "";

        if (empty($file['name'])) {
            $photoProfil = 'random-user.png';
        } else {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, $authorizedExtensions)) {
                $this->session->addFlash('danger', 'La photo doit être de type: jpeg, jpg, png !');
            } elseif (
                file_exists($file['tmp_name']) &&
                filesize($file['tmp_name']) > $maxFileSize
            ) {
                $this->session->addFlash('danger', 'Le fichier ne doit pas dépasser 1Mo');
            }

            $photoProfil = uniqid() . '.' . $extension;
            move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $photoProfil);
        }
        return $photoProfil;
    }

    public function updatePhotoProfil(array $file): string
    {
        $user = $this->session->get('user');

        $uploadDir = 'upload/profil';
        $authorizedExtensions = ['jpeg', 'jpg', 'png'];
        $maxFileSize = 1000000;
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $photoProfil = "";

        if (empty($file['name'])) {
            $photoProfil = $user['avatar'];
        } else {
            $oldImgProfil = "upload/profil/" . $user['avatar'];

            if ($user['avatar'] !== "random-user.png") {
                unlink($oldImgProfil);
            }

            if (!in_array($extension, $authorizedExtensions)) {
                $this->session->addFlash('danger', 'La photo doit être de type: jpeg, jpg, png !');
            }

            if (file_exists($file['tmp_name']) && filesize($file['tmp_name']) > $maxFileSize) {
                $this->session->addFlash('danger', 'Le fichier ne doit pas dépasser 1Mo');
            }

            $photoProfil = uniqid() . '.' . $extension;
            move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $photoProfil);
        }

        return $photoProfil;
    }
}
