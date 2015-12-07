<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 9-7-2015
 * Time: 19:54
 */
class Core{

    private $page = null;
    private $delay = 0;

    public function loadPage($page){
        $this->page = $page;
    }
    public function delay($d){
        $this->delay = $d;
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
        setTimeout(function(){
            window.open("'.$this->page.'","'.$target.'");
        },'.$this->delay.');
    </script>
    ';
        }
    }
    public function notAllowed(){

        echo '
        <article class="text-box">
            <span class="error">U hebt niet de juiste rechten om deze pagina te bekijken.</span>
        </article>
        ';
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
    public function getHour($name,$date=false){

        return '
            <select name="'.$name.'_hour" id="form_'.$name.'_hour">
                <option value="00">00</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
            </select>
            <script>
                document.getElementById("form_'.$name.'_hour").value = "'.($date != false ? date("H", strtotime($date)) : date("H")).'";
            </script>
        ';
    }
    public function getMinutes($name,$date=false){


        //option[value="$"]{$}*60

        return '
            <select name="'.$name.'_minutes" id="form_'.$name.'_minutes">
                <option value="00">00</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
                <option value="32">32</option>
                <option value="33">33</option>
                <option value="34">34</option>
                <option value="35">35</option>
                <option value="36">36</option>
                <option value="37">37</option>
                <option value="38">38</option>
                <option value="39">39</option>
                <option value="40">40</option>
                <option value="41">41</option>
                <option value="42">42</option>
                <option value="43">43</option>
                <option value="44">44</option>
                <option value="45">45</option>
                <option value="46">46</option>
                <option value="47">47</option>
                <option value="48">48</option>
                <option value="49">49</option>
                <option value="50">50</option>
                <option value="51">51</option>
                <option value="52">52</option>
                <option value="53">53</option>
                <option value="54">54</option>
                <option value="55">55</option>
                <option value="56">56</option>
                <option value="57">57</option>
                <option value="58">58</option>
                <option value="59">59</option>
            </select>
            <script>
                document.getElementById("form_'.$name.'_minutes").value = "'.($date != false ? date("i", strtotime($date)) : date("i")).'";
            </script>
        ';
    }


}