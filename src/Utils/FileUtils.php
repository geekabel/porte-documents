<?php


namespace App\Utils;


trait  FileUtils
{


    private  function getDirectory(string $path) : string {
        return $this->porteDocumentBasePath . DIRECTORY_SEPARATOR . $path;
    }
    private  function getFielPath(string $path,string $fileName): string  {
        return $this->getDirectory($path) . DIRECTORY_SEPARATOR . $fileName;
    }

}