<?php 
  namespace app\models;
    class ChartModel {
        public function makeArrayStr($array){
            $str="[";
            $count=0;
            if(!$array) return "[]";
            foreach($array as $element){
                $count+=1;
                if($count!=count($array)){
                    $str.="'".$element."',";
                }
                else{
                    $str.="'".$element."']";
                }
            }
            return $str;
        }
        public function makeArrayNum($array){
            $str="[";
            $count=0;
            if(!$array) return "[]";
            foreach($array as $element){
                $count+=1;
                if($count!=count($array)){
                    $str.=$element.",";
                }
                else{
                    $str.=$element."]";
                }
            }
            return $str;
        }

        public function lineChart($xlabels,$ylabels,$xvalues,$yvalues,$label,$color,$canvasname){
            $xlabels=$this->makeArrayStr($xlabels);
            $ylabels=$this->makeArrayStr($ylabels);
            $xvalues=$this->makeArrayNum($xvalues);
            $yvalues=$this->makeArrayNum($yvalues);
            return sprintf('
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
                <script>
                var xValues =%s;
                var yValues = %s;

                new Chart("myChart%s", {
                type: "line",
                data: {
                    labels: %s,
                    datasets: [{
                    label:"%s",
                    fill: false,
                    pointRadius: 1,
                    borderColor: "%s",
                    data: %s
                    }]
                },
                options: {}
                });

                


                </script>',$xvalues,$yvalues,$canvasname,$xlabels,$label,$color,$xvalues );
        }
    }



?>