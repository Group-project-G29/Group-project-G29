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

        public function lineChart($xlabels,$ylabels,$xvalues,$yvalues,$label,$color,$canvasname,$min,$max){
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
                options: {
                      scales: {
            y: {
                min:%s,
                max: %s
                }
            }

                }
                });

                


                </script>',$xvalues,$yvalues,$canvasname,$xlabels,$label,$color,$xvalues);
        }
         public function lineChartAssis($xlabels,$ylabels,$xvalues,$yvalues,$label,$color,$canvasname){
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
                options: {
                     
            }

                
                });

                


                </script>',$xvalues,$yvalues,join("-",explode(" ",trim($canvasname))),$xlabels,join("-",explode(" ",trim($label))),$color,$xvalues);
        }

        public function barchart($xlabels,$ylabels,$xvalues,$yvalues,$label,$color,$canvasname,$text){
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
                type: "bar",
                data: {
                    labels:%s,
                    datasets: [{
                    label:"%s",
                    backgroundColor:["lightgreen", "aqua", "pink", "yellow", "lightblue", "gold"],
                    data: %s
                    }]
                },
                options: {
                    indexAxis: "x",
                    title: {
                    display: true,
                    text:"%s"
                    }
                }
            });

                </script>',$xvalues,$yvalues,$canvasname,$xlabels,$text,$xvalues,$text );   
            }

            public function barcharth($xlabels,$ylabels,$xvalues,$yvalues,$label,$color,$canvasname,$text){
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
                    type: "bar",
                    data: {
                        labels:%s,
                        datasets: [{
                        label:"%s",
                        backgroundColor:["lightgreen", "aqua", "pink", "yellow", "lightblue", "gold"],
                        data: %s
                        }]
                    },
                    options: {
                        indexAxis: "y",
                        title: {
                        display: true,
                        text:"%s"
                        }
                    }
                });

                    </script>',$xvalues,$yvalues,$canvasname,$xlabels,$text,$xvalues,$text );   
            }

            public function piechart($xvalues,$yvalues,$canvasname,$text){
                $xvalues=$this->makeArrayNum($xvalues);
                $yvalues=$this->makeArrayNum($yvalues); 
               
                return sprintf('
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
                    <script>
                    new Chart("myChart%s", {
                    type: "pie",
                    data: {
                        labels:%s,
                        datasets: [{
                        backgroundColor:["lightgreen", "aqua", "pink", "yellow", "lightblue", "gold"],
                        data: %s
                        }]
                    },
                    options: {
                        title: {
                        display: true,
                        text: "%s"
                        }
                    }
                    })  

                    </script>',$canvasname,$xvalues,$yvalues,$text );   
            }
            
    }
    
    



?>