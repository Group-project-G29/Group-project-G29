<section class="allocation-chart">
    <div class="chart-div">
        <div class="chart-tital">Allocation Chart</div>
    </div>
</section>

<?php 
$todayName = date("l");
$week = array("Monday"=>"green", "Tuesday"=>"green", "Wednesday"=>"green", "Thursday"=>"green", "Friday"=>"green", "Saturday"=>"green");
foreach($week as $x => $x_value) {
    if($x == $todayName){
        $week[$x] = "yellow";
        break;
    }
    else{
        $week[$x] = "red";
    }
}

?>

<section class="allocation-days">
    <div class="day <?=$week['Monday'] ?>" id="Monday" onclick="show('monday')">Monday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="monday"  <?php if($todayName!=="Monday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Monday'].'-g' ?>">
        <?php foreach($monday as $key=>$channel): ?>
                <div class="channel-deatails <?=$week['Monday'].'-b' ?>">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$monday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>

    <div class="day <?=$week['Tuesday'] ?>" id="Tuesday" onclick="show('tuesday')">Tuesday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="tuesday"  <?php if($todayName!=="Tuesday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Tuesday'].'-g' ?>">
        <?php foreach($tuesday as $key=>$channel): ?>
                <div class="channel-deatails  <?=$week['Tuesday'].'-b' ?>">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$tuesday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>

    <div class="day <?=$week['Wednesday'] ?>" id="Wednesday" onclick="show('wednesday')">Wednesday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="wednesday" <?php if($todayName!=="Wednesday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Wednesday'].'-g' ?>">
        <?php foreach($wednesday as $key=>$channel): ?>
                <div class="channel-deatails <?=$week['Wednesday'].'-b' ?>">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$wednesday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>

    <div class="day <?=$week['Thursday'] ?>" id="Thursday" onclick="show('thursday')">Thursday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="thursday" <?php if($todayName!=="Thursday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Thursday'].'-g' ?>">
        <?php foreach($thursday as $key=>$channel): ?>
                <div class="channel-deatails <?=$week['Thursday'].'-b' ?>">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$thursday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>

    <div class="day <?=$week['Friday'] ?>" id="Friday" onclick="show('friday')">Friday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="friday" <?php if($todayName!=="Friday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Friday'].'-g' ?>">
        <?php foreach($friday as $key=>$channel): ?>
                <div class="channel-deatails <?=$week['Friday'].'-b' ?>">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$friday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>

    <div class="day <?=$week['Saturday'] ?>" id="Saturday" onclick="show('saturday')">Saturday <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
    <div id="saturday" <?php if($todayName!=="Saturday") {echo("hidden");} ?>>
        <div class="channel-data <?=$week['Saturday'].'-g' ?>">
        <?php foreach($saturday as $key=>$channel): ?>
                <div class="channel-deatails">
                    <div class="channel-deatail-1"><?=$channel['speciality'].' - '.$channel['name']?></div>
                    <div class="channel-deatail-2"><?='Time'.' - '.$channel['time'].' to '.$channel['time']?></div>
                    <div class="channel-deatail-3"><?='Room'.' - '.$channel['room']?></div>
                </div>
        <?php endforeach; ?>
        <?php if(!$saturday){ ?> <div class="no-channelings">No Channeling Sessions</div> <?php } ?>
        </div>
    </div>
</section>


<?php 
// $current_dayname = date("l");
             
// echo $date = date("Y-m-d",strtotime('monday this week')).' To '.date("Y-m-d",strtotime("sunday this week")); 
// // var_dump($channeling);

// echo $current_dayname = date("l"); // return sunday monday tuesday etc.
             
// $date = date("Y-m-d",strtotime('monday this week')).'to'.date("Y-m-d",strtotime("$current_dayname this week")); 
// var_dump($date);

// // set current date
// $date = date("Y-m-d");
// // parse about any English textual datetime description into a Unix timestamp 
// $ts = strtotime($date);
// // find the year (ISO-8601 year number) and the current week
// $year = date('o', $ts);
// $week = date('W', $ts);
// // print week for the current date
// for($i = 1; $i <= 7; $i++) {
//     // timestamp from ISO week date format
//     $ts = strtotime($year.'W'.$week.$i);
//     print date("Y-m-d l", $ts) . "<br>";
// }
?>

<script>
    function show(day){
        var x = document.getElementById(day);
        if (x.hidden === true) {
            x.hidden = false;
        } else {
            x.hidden = true;
        }
    }
</script>