<?php
 namespace App\Configuration;


 class FileLocations
 {

    /** @var array */
    private $locations = [];

   

    public function __construct()
    {
        
    }

    public function get(string $locationName): ?FileLocation
    {
        if (array_key_exists($locationName, $this->locations)) {
            return $this->locations[$locationName];
        }

        return null;
    }

 }