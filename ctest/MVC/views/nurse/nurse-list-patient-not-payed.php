<?php if(isset($qNumber)){ ?>

    <div class="detail-row">

        <div class="column-flex">
            <div class="main-detail-title">
                <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
            </div>
            <div class="patient-change-div">
                
                <div class="number-content">
                    <h2>Patient Number</h2>
                    <div class="number-pad">
                        <div class="number-item fs-200"><?=$patient['queue_no']?></div>
                    </div>
                    <div class="move-number-div">
                        <input type="number" id="move-number2" name="move-number2">
                        <button onclick="change(<?=$id?>)">Move</button>
                    </div>
                    <div class="patient-numbers">
                        <div class="payment-not-successfull">Payment is not Successfull</div>
                        <div class="ptient-count">Total Patients : <span><?=$payedAppoCount?></span></div>
                        <div class="ptient-count">Previous Patient Number : <span><?=$prevNum?></span></div>
                    </div>
                    
                </div>
            
            </div>
    
        </div>

    </div>

    <div class="detail-row">
        <div class="scheduled-info fs-100">
            <center><span>Patient Name : <?=$patient['name']?></span></center>
        </div>
    
        <div class="patient-details">
            <span>Age : <?=$patient['age']?></span>
            <span>Gender : <?=$patient['gender'] ?></span>
            <span>Contact No : <?=$patient['contact'] ?></span>
        </div> 
    </div>


<?php } else{ ?>
    <div class="detail-row">

        <div class="column-flex">
            <div class="main-detail-title">
                <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
            </div>
            <div class="patient-change-div">
                
                <div class="number-content">
                    
                    <div class="move-number-div">
                        <input type="number" id="move-number2" name="move-number2">
                        <button onclick="change(<?=$id?>)">Move</button>
                    </div>
                    <div class="patient-numbers">
                        <div class="payment-not-successfull">No patient for this Queue Number</div>
                        <div class="ptient-count">Total Patients : <span><?=$payedAppoCount?></span></div>
                        <div class="ptient-count">Previous Patient Number : <span><?=$prevNum?></span></div>
                    </div>
                    
                </div>
            
            </div>
    
        </div>

    </div>

<?php }  ?>


<script>
    function change(id){
        const num = document.getElementById("move-number2").value;
        const num1 = num;
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&cmd=move&prevNum='+<?=$prevNum?>;

    }
</script>