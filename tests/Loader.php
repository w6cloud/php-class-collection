<?php

require_once(__DIR__.'/../vendor/autoload.php');

class Loader {

    public function __construct(){
        $this->Models = new \W6\ClassCollection\ClassCollection(
            __DIR__ . "/Models",
            [
                'classTemplate' => '\Test\Models\\\$1'
            ]
        );
        $this->Models->load();
        $this->Modules = new \W6\ClassCollection\ClassCollection(
            __DIR__ . "/Modules/VisualComposer",
            [
                'fileRegex'     => '#.*/(.*?)/Module\.php#',
                'classTemplate' => '\Test\Modules\VisualComposer\\\$1\Module'
            ]
        );
        $this->Modules->load();
    }

}


$loader = new Loader();

print_r($loader);