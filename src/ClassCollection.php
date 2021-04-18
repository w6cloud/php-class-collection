<?php

namespace W6\ClassCollection;

class ClassCollection 
{
    use ClassCollectionTrait;

    protected $folder;

    protected $options;

    public function __construct( $folder, $options = [] ) 
    {
        $this->folder = $folder;
        $this->options = $options;
    }

    public function load()
    {
        $this->loadClasses( $this->folder, $this->options );
    }

}