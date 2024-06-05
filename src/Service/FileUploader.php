<?php

namespace App\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory = null;
    private $fileName = null;

    public function upload(UploadedFile $file): void
    {
        $this->fileName = 'img-'.uniqid().'.'.$file->guessExtension();

        if ($this->targetDirectory === null) {
            throw new Exception("Uploader need a target directory. Use 'setTargetDirectory' to set one.", 500);
        }

        try {
            $file->move($this->getTargetDirectory(), $this->fileName);
        } catch (FileException $e) {
            throw $e;
        }
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setTargetDirectory(string $dir)
    {
        $this->targetDirectory = $dir;
    }

    public function getTargetDirectory(): ?string
    {
        return $this->targetDirectory;
    }
}