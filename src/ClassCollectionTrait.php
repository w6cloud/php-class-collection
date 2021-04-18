<?php
/**
 * Charge les classes d'un dossier comme attributs de la classe surchargée
 * 
 * Par exemple, on a un dossier Helpers contenant yous les Helpers de l'application (HtmlHelper, AlertHelper...)
 * Je veux regrouper tous ces Helpers dans une classe pour y accéder facilement. Je crée donc la class HelperCollection comme suit :
 * 
 * use \W6\ClassCollection\ClassCollectionTrait;
 * 
 * class HelperCollection {
 * 
 *      use \W6\ClassCollection\ClassCollectionTrait;
 *      
 *      public function __construct(){
 *          $this->getClasses( 'View/Helpers' );
 *      }
 * }
 * 
 * // Usage :
 * $Helpers = new HelperCollection();
 * $Helpers->Html->link('Link text', '#');
 * 
 * @package W6\ClassCollection
 */


namespace W6\ClassCollection;

trait ClassCollectionTrait {

    public function loadClasses( $folder, $options = [] )
    {
        $options = $options + [
            'fileRegex'     => '#.*/(.*?)\.php#',
            'classRegex'    => '#^(.*?)$#',
            'classTemplate' => '$1'
        ];
        extract( $options );
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( $folder )
        );
        foreach ($rii as $file) {
            if ($file->isDir()) { 
                continue;
            }
            $path = $file->getPathname();
            if ( preg_match( $fileRegex, $path, $matches ) ) {
                $class = preg_replace( $classRegex, $classTemplate, $matches[1], 1 );
                if (!class_exists($class)) {
                    require_once $path;
                }
                //print_r(compact('path', 'class', 'matches'));
                $this->{$matches[1]} = new $class();
            }
        }
    }

}
