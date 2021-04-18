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

trait ClassCollection {

    /**
     * Nom du fichier PHP contenant la classe dans une organisation en dossiers / sous-dossiers.
     * 
     * Par exemple pour les widgets la structure est :
     * - widgets
     * -- SidebarName
     * --- SampleWidget
     * ---- Widget.php
     * --- OtherWidget
     * ---- Widget.php
     * 
     * Dans ce cas, on indique "Widget" et les membres créés seont :
     * $this->SidebarNameSampleWidget (classe chargée dans widgets/SidebarName/SampleWidget/Widget.php)
     * $this->SidebarNameOtherWidget (classe chargée dans widgets/SidebarName/OtherWidget/Widget.php)
     * 
     * Si non renseigné, c'est le fonctionnement par défaut qui sera utilisé.
     * 
     * @var string
     */
    public $inSubFolderFileName = null;

    /**
     * Nom du fichier PHP contenant la classe dans une organisation en dossiers.
     * 
     * Par exemple pour les widgets la structure est :
     * - widgets
     * -- SampleWidget
     * --- Widget.php
     * -- OtherWidget
     * --- Widget.php
     * 
     * Dans ce cas, on indique "Widget" et les membres créés seront :
     * $this->SampleWidget (classe chargée dans widgets/SampleWidget/Widget.php)
     * $this->OtherWidget (classe chargée dans widgets/OtherWidget/Widget.php)
     * 
     * Si non renseigné, c'est le fonctionnement par défaut qui sera utilisé.
     * 
     * @var string
     */
    public $inFolderFileName = null;

    public function getClasses( $path ) {

        $folderPath = ROOT . "/libs/$path";

        if( !empty($this->inSubFolderFileName) ){

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator( $path )
                ), 
                '/^.+\/(.+)\/(.+)\/' . $this->inSubFolderFileName . '\.php$/i', 
                \RecursiveRegexIterator::GET_MATCH
            );
            foreach ($iterator as $file) {
                $prop = $file[1] . $file[2];
                $class = $file[1] . '/' . $file[2];
                $classPath = '\BkTheme\\' . ltrim( str_replace( '/', '\\', "$path/$class/{$this->inSubFolderFileName}" ), '\\' );
                $this->$prop = new $classPath();
            }

        } elseif( !empty($this->inFolderFileName) ){

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator( $path )
                ), 
                '/^.+\/(.+)\/' . $this->inFolderFileName . '\.php$/i', 
                \RecursiveRegexIterator::GET_MATCH
            );
            foreach ($iterator as $file) {
                $prop = $file[1];
                $classPath = '\BkTheme\\' . ltrim( str_replace( '/', '\\', "$path/$prop/{$this->inFolderFileName}" ), '\\' );
                $this->$prop = new $classPath();
            }

        } else {

            $files = glob( "$path/*.php", GLOB_BRACE );
            foreach( $files as $file ){
                $class = pathinfo( $file, PATHINFO_FILENAME );
                $classPath = '\BkTheme\\' . ltrim( str_replace( '/', '\\', "$path/$class" ), '\\' );
                $this->$class = new $classPath();
            }

        }
    }

    public function getClassNames( $path ) {

        $folderPath = ROOT . "/libs/$path";
        $classes = [];

        if( !empty($this->inSubFolderFileName) ){

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator( $folderPath )
                ), 
                '/^.+\/(.+)\/(.+)\/' . $this->inSubFolderFileName . '\.php$/i', 
                \RecursiveRegexIterator::GET_MATCH
            );
            foreach ($iterator as $file) {
                $classes[] = $file[1] . $file[2];
            }

        } elseif( !empty($this->inFolderFileName) ){

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator( $folderPath )
                ), 
                '/^.+\/(.+)\/' . $this->inFolderFileName . '\.php$/i', 
                \RecursiveRegexIterator::GET_MATCH
            );
            foreach ($iterator as $file) {
                $classes[] = $file[1];
            }

        } else {

            $files = glob( ROOT . "/libs/$path/*.php", GLOB_BRACE );
            foreach( $files as $file ){
                $classes[] = pathinfo( $file, PATHINFO_FILENAME );
            }
            
        }

        return $classes;
    }

}
