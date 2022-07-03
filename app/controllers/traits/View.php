<?php 
    
    namespace app\controllers\traits;

use app\Config;
use app\config\TwigConfig;
use app\functions\Load;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

    /**
     * 
     */
    trait View
    {
        
        protected $twig;

        protected function twig(){

            $loader = new FilesystemLoader('../app/views');
            $config = new TwigConfig();
            $this->twig = new Environment($loader, $config->getConfig());
        }

        protected function functions(){
            $functions = Load::file('/app/functions/TwigExtendedFunctions.php');

            foreach($functions as $function){
                $this->twig->addFunction($function);
            }
        }

        protected function load(){
            $this->twig();

            $this->functions();
        }

        protected function view($view,$data){

            try {
                $this->load();

                $data['BASEHREF'] = BASEHREF;
                $data['REQUEST_URI'] = REQUEST_URI;
                $data['USER_DATA'] = $_SESSION['LOGIN']['USER_DATA'] ?? '';
                $template = $this->twig->load(str_replace('.', '/', $view).'.twig');

                return $template->display($data);
            }finally{
                unset($_SESSION['FLASH']);
            }
        }

        protected function render($view,$data) {
            $this->load();
            
            unset($_SESSION['FLASH']);
            $data['BASEHREF']= BASEHREF;
            $data['REQUEST_URI']= REQUEST_URI;
            $data['ABSPATH']= ABSPATH;
            $template = $this->twig->load(str_replace('.','/',$view).'.html');

            return $template->render($data);
        }
                
    }
    