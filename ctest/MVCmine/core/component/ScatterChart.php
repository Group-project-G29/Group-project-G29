<?php
    namespace app\core\component;

    class ScatterChart{
        public array $array; //['monday'=>['input','input]];

        public function __construct($array,$scale,$values){
            $this->array=$array;
            $this->scale=$scale;   
            $this->values=$values;
        }

        public function __toString(){
            $colors=['red','orange','green'];
            $random_keys=array_rand($colors,1);
            $color=$colors[$random_keys];
            $divcontent=[];
            foreach(array_keys($this->array) as $day){
                $i=0;
                $str=0;
                foreach($this->array[$day] as $content){
                    $pos=($this->values[$day][$i]/$this->scale)*100;
                    $str.="<div  class='scatter-chart-content' style='margin-top:$pos; margin-left:1vw; background-color:$color'>$content</div>";
                    $i++;
                }
                $divcontent[$day]=$str;
            }

            return sprintf(
                "<div>
                    <table border='0' class='scatter-chart-table'>
                        <tr>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td' >%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                            <td>
                                <div class='scatter-chart-table-td'>%s</div>
                            </td>
                        </tr>
                        <tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
                    </table>
                </div>",
            $divcontent['monday']??' ',$divcontent['tuesday']??'',$divcontent['wednesday']??'',$divcontent['thursday']??'',$divcontent['friday']??'',$divcontent['saturday']??'',$divcontent['sunday']??'');
        }


    }



?>