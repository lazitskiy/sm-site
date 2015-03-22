<?php
    class Index extends F3instance{
        private $db;
        
        function __construct(){
            $this->set('title','Хуй пизда Джигурда');
            $this->db = $this->get('DB');
            echo $this->render($this->get('_header'));
        }
        public function index(){
            
            echo $this->render('app/view/index/index.php');

        }
        function __destruct(){
            echo $this->render($this->get('_footer'));
        }
    }
?>
