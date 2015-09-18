<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 9-7-2015
 * Time: 19:54
 */
class Core{

    private $page = null;
    public function loadPage($page){
        $this->page = $page;
    }
    public function checkLoad(){
        if($this->page != null){
            if((strpos($this->page,',') !== false)){
                $splitted = explode(",",$this->page);
                $this->page = $splitted[0];
                $target = $splitted[1];
            }else{
                $target = "_top";
            }
            echo '
    <script>
        window.open("'.$this->page.'","'.$target.'");
    </script>
    ';
        }
    }


}