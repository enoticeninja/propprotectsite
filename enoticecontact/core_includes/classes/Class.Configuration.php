<?php 
class Configuration {
    public static $configuration = array();
    
    public function __construct(){

        $this->configuration['PS_PNG_QUALITY'] = 9;
        $this->configuration['PS_JPEG_QUALITY'] = 100;
        $this->configuration['PS_IMAGE_GENERATION_METHOD'] = 1;
        $this->configuration['PS_IMAGE_QUALITY'] = '';        
    }
    
    public static function get($key){   
        $configuration['PS_PNG_QUALITY'] = 9;
        $configuration['PS_JPEG_QUALITY'] = 100;
        $configuration['PS_IMAGE_GENERATION_METHOD'] = 0;
        $configuration['PS_IMAGE_QUALITY'] = 'jpg';       
        $configuration['PS_IMAGE_QUALITY'] = 'png_all';       
       if(isset($configuration[$key])){
            return $configuration[$key];
       }
       else{
           return null;
       }
    }

}
?>