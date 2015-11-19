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

    public function getDay($name,$date=false){
        return '
            <select name="'.$name.'_day" id="form_'.$name.'_day">
                <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
            </select>
            <script>
                document.getElementById("form_'.$name.'_day").value = "'.($date != false ? date("j", strtotime($date)) : date("j")).'";
            </script>
        ';

    }

    public function getMonth($name,$date=false){
        return '
            <select name="'.$name.'_month" id="form_'.$name.'_month">
                <option value="1">Januari</option><option value="2">Februari</option><option value="3">Maart</option><option value="4">April</option><option value="5">Mei</option><option value="6">Juni</option><option value="7">Juli</option><option value="8">Augustus</option><option value="9">September</option><option value="10">Oktober</option><option value="11">November</option><option value="12">December</option>
            </select>
            <script>
                document.getElementById("form_'.$name.'_month").value = "'.($date != false ? date("n", strtotime($date)) : date("n")).'";
            </script>
        ';
    }

    public function getYear($name,$date=false){
        return '
            <select name="'.$name.'_year" id="form_'.$name.'_year">
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
            </select>
            <script>

                document.getElementById("form_'.$name.'_year").value = "'.($date != false ? date("Y", strtotime($date)) : date("Y")).'";
            </script>
        ';
    }


}