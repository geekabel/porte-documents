<?php

namespace App\Service;




class PorteDocumentConfig
{

    private string $porteDocumentBasePath;

    public function __construct(string $porteDocumentBasePath)
    {
        $this->porteDocumentBasePath = $porteDocumentBasePath;
    }

    public function getBasePath()
    {
        return $this->porteDocumentBasePath;
    }

   
}

