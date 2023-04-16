<?php
namespace app\models;

use app\core\DbModel;

    class PreChannelingTest extends DbModel {

        public string $metric='';
        public string $name=''; 
        //return array like  
        public function mainGetAllTestValues($patient){
            $test=$this->getDistinctTests($patient);
            $array=[];
            foreach($test  as  $element){
                if(isset($element['test_ID']) && $element['test_ID'])
                $array[$element['name']]=$this->getTestChanneling($element['test_ID'],$patient);
            }
            return $array;
        }

        //get all the tests realted to channeling
        public function getAllTestData($channelingID){
            return $this->customFetchAll("select  DISTINCT test.name,test.metric from appointment as a left join opened_channeling as o on a.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join pre_channeilng_test_aloc as aloc on aloc.channeling_ID=c.channeling_ID left join pre_channeling_tests_values as tval on tval.test_ID=aloc.test_ID left join pre_channeling_tests as test on test.test_ID=aloc.test_ID  where c.channeling_ID=".$channelingID);
        }
        //get all the distict tests related to a patient
        public function getDistinctTests($patient){
            return $this->customFetchAll("select distinct test.name,test.metric,test.test_ID from appointment as a left join opened_channeling as o on a.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join pre_channeilng_test_aloc as aloc on aloc.channeling_ID=c.channeling_ID left join pre_channeling_tests as test on test.test_ID=aloc.test_ID  where a.patient_ID=".$patient);
        }
        //get array with all past channeling test values and dates in the same array
        public function getTestChanneling($test,$patient){
            $result=$this->customFetchAll("select  * from pre_channeling_tests_values  where test_ID=".$test." and patient_ID=".$patient);
            $values=[];
            $day=[];
            foreach($result as $el){
                array_push($values,$el['value']);
                array_push($day,$el['value_inserted_date']);
            }
            return ['data'=>$values,'labels'=>$day];

        }
        public function getAssistanceValue($patient,$openedchannelingID){
            return $this->customFetchAll("select distinct test.name,tval.value,test.metric from appointment as a left join opened_channeling as o on a.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join pre_channeilng_test_aloc as aloc on aloc.channeling_ID=c.channeling_ID  left join pre_channeling_tests_values as tval on tval.test_ID=aloc.test_ID left join pre_channeling_tests as test on test.test_ID=aloc.test_ID where a.opened_channeling_ID=".$openedchannelingID." and tval.patient_ID=".$patient);
        }
        //get all the test realted to opened to channeling
        public function getPatientTestResults($patient,$testID){
            return $this->fetchAssocAllByName(['patient_ID'=>$patient,'test_ID'=>$testID],'pre_channeling_tests_values');
        }
        public function getIDbyName($name){
            return $this->fetchAssocAll(['name'=>$name])[0]['test_ID'];
        }
      
        //set channeling test values
        public function addChannelingTestValues($value,$test_ID,$appointment_ID,$patientID){
            return $this->customFetchAll("insert into pre_channeling_tests_values (value,test_ID,appointment_ID,patient_ID) values('$value','$test_ID','$appointment_ID',$patientID)");
        }
        //allocate channeling tests 
        public function allocateChannelingTest($testID,$channelingID){
            return $this->customFetchAll("insert into pre_channeilng_test_aloc values($channelingID,$testID)");
        }
        public function getAllTests(){
            $result=$this->customFetchAll("select * from pre_channeling_tests ");
            $array=[];
            foreach($result as $element){
                $array[$element['name']]=$element['name'];
            }
            return $array;

        }
        public function isExist($channelingID,$testID){
            $result=$this->fetchAssocAllByName(['channeling_ID'=>$channelingID,'test_ID'=>$testID],' pre_channeilng_test_aloc ');
            if($result){
                return true;
            }
            else return false;

        }
        public function fileDestination(): array
        {
            return [];
        }
        public function rules():array{
            return [];
        }
        public function tableName(): string
        {
            return 'pre_channeling_tests';
        }
        public function primaryKey(): string
        {
            return 'test_ID';
        }
        
        public function tableRecords(): array{
            return ['pre_channeling_tests'=>['metric','name']];
        }

        public function attributes(): array
        {
            return ['metric','name' ];
        }

    }

?>