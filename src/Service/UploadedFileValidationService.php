<?php

namespace App\Service;

use App\Model\ProductManager;
use App\Service\SessionManager;

class UploadedFileValidationService
{
    protected $session;
    protected $productManager;
    protected $uploadFile;

    public function __construct()
    {
        $this->session = new SessionManager();
        $this->productManager = new ProductManager();
    }

    public function validateFileUpload(array $file): array
    {
        $uploadDir = 'upload/';
        $authorizedExtensions = ['bmp', 'jpeg', 'jpg', 'png', 'gif', 'webp'];
        $maxFileSize = 1000 * 1000;
        $result = ['success' => false, 'uploadFile' => null];
        if (!empty($file['name'])) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uniqueFilename = uniqid();
            $uploadFile = $uploadDir . $uniqueFilename . '.' . $extension;

            if (!in_array($extension, $authorizedExtensions)) {
                $this->session->addFlash('danger', 'Le type de fichier n\'est pas autorisé');
            } elseif (file_exists($uploadFile)) {
                $this->session->addFlash('danger', 'Le fichier existe déjà');
            } elseif (file_exists($file['tmp_name']) && filesize($file['tmp_name']) > $maxFileSize) {
                $this->session->addFlash('danger', 'Le fichier est trop volumineux');
            } elseif (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $this->session->addFlash('danger', 'Erreur lors de l\'upload');
            } else {
                $result['success'] = true;
                $result['uploadFile'] = $uploadFile;
            }
        }
        return $result;
    }

    protected function getFileInfo(array $file, array $authorizedExtensions, int $maxFileSize): ?array
    {
        $fileInfo = [];
        $fileInfo['extension'] = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileInfo['uniqueFilename'] = uniqid();

        if (!in_array($fileInfo['extension'], $authorizedExtensions)) {
            $this->session->addFlash('danger', 'Le type de fichier n\'est pas autorisé');
            return null;
        }

        if ($file['size'] > $maxFileSize) {
            $this->session->addFlash('danger', 'Le fichier est trop volumineux');
            return null;
        }

        return $fileInfo;
    }


    public function validateUpdatedFile(array $file, string $currentIllustration): bool
    {
        $uploadServiceResult = $this->validateFileUpload($file);
        if (!$uploadServiceResult['success']) {
              return false;
        }
        $uploadFile = $uploadServiceResult['uploadFile'];

        if (file_exists($currentIllustration)) {
             unlink($currentIllustration);
        }
        $this->uploadFile = $uploadFile;

        return true;
    }


    public function getUploadFile(): ?string
    {
        return $this->uploadFile ?? null;
    }
}
