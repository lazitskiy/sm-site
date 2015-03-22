<?php


    class Tools extends F3instance {

        private $db;
        private $trans;
        private $space;

        function __construct() { 
            $this->db = $this->get('DB');
            set_time_limit(9000);
        }

        function resize(){
            include($_SERVER['DOCUMENT_ROOT'].'/lib/wideimage/WideImage.php');            
            $films = $this->db->sql('SELECT id FROM films ORDER BY id LIMIT 14000,500');
            foreach($films as $f){
                $dir = $_SERVER['DOCUMENT_ROOT'].'/images/film-'.$f['id'];
                if(is_dir($dir)){
                    $rdir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), TRUE);
                    foreach ($rdir as $file){
                        $f_name = $file->getPathname();

                        if( strpos($f_name,'.jpg') && is_file($f_name) ){
                            $ex = explode('.jpg',$f_name); 
                            $info = getimagesize($f_name);      
                            //На постер 
                            if( strpos($f_name,'-poster') ){
                                if($info[0]>500 && !file_exists($file->getPath().'/img-poster.jpg')){ 
                                    try{ 
                                        WideImage::load($f_name)->resize(479)->saveToFile($f_name,80);
                                    }catch(Exception $e){        
                                        echo'постер'.$ex[0]."<br/>";
                                    }
                                }
                                if($info[0]>150 && !file_exists($file->getPath().'/img-poster-150.jpg')){
                                    try{ 
                                        WideImage::load($f_name)->resize(150)->saveToFile($ex[0].'-150.jpg',80);
                                    }catch(Exception $e){        
                                        echo'постер'.$ex[0]."<br/>";
                                    }
                                }

                                if(!file_exists($file->getPath().'/img-poster-orig.jpg')){
                                    try{ 
                                        WideImage::load($f_name)->saveToFile($ex[0].'-orig.jpg',80);
                                    }catch(Exception $e){        
                                        echo'постер'.$ex[0]."<br/>";
                                    }
                                }
                            }else{
                                if($info[0]>160 && !file_exists($ex[0].'-157.jpg')){
                                    try{
                                        WideImage::load($f_name)->resize(157)->saveToFile($ex[0].'-157.jpg',80);
                                    }catch(Exception $e){        
                                        echo'картинка хуйня '.$ex[0]."<br/>";
                                    }

                                }
                            }
                        }
                    }
                }
            }
            echo 'ok';
        }



    }
?>
