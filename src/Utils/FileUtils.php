<?php


namespace App\Utils;


trait  FileUtils
{


    private  function getDirectory(string $path) : string {
        return $this->porteDocumentBasePath . DIRECTORY_SEPARATOR . $path;
    }

    private  function getTrashDirectory(string $path) : string {
        if (!is_dir($this->trashBasePath . DIRECTORY_SEPARATOR . $path))
             mkdir($this->trashBasePath . DIRECTORY_SEPARATOR . $path);
        return $this->trashBasePath . DIRECTORY_SEPARATOR . $path;
    }

    private  function getTrashFilePath(string $path,string $fileName) : string {
        return $this->getTrashDirectory($path) . DIRECTORY_SEPARATOR . $fileName;
    }
    private  function getFielPath(string $path,string $fileName): string  {
        return $this->getDirectory($path) . DIRECTORY_SEPARATOR . $fileName;
    }

}