
<?php
use app\models\SMS;
use app\models\Patient;

 $sms=new SMS();
 $patient=new Patient();
 $patient->name="Nimantha";
 $patient->contact="0774368092";
 $text="hi";
    $sms->WriteSMS($text,$patient);
 ?>