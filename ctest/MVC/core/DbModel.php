<?php
    namespace app\core;

    abstract class DbModel extends FileModel{
        
        abstract public function tableRecords():array;
        abstract public function attributes():array;
        abstract public function tableName():string;
        abstract public function primaryKey():string;

        public function save(){
            $this->fileStore();
            $tablerecords=$this->tableRecords();
            foreach($tablerecords as $tablename=>$attributes){
                $params=array_map(fn($attr)=>":$attr",$attributes);
                $statement=self::prepare("Insert into $tablename (".implode(',',$attributes).") VALUES (".implode(',',$params).")");
                foreach ($attributes as $attribute){
                     $statement->bindValue(":$attribute",$this->{$attribute});
                     echo $this->{$attribute} ;
                }
                $statement->execute();
            }
            return $this->customFetchAll("select last_insert_id()");
           
        }
        public function savenofiles(){
         
            $tablerecords=$this->tableRecords();
            foreach($tablerecords as $tablename=>$attributes){
                $params=array_map(fn($attr)=>":$attr",$attributes);
                $statement=self::prepare("Insert into $tablename (".implode(',',$attributes).") VALUES (".implode(',',$params).")");
                foreach ($attributes as $attribute){
                     $statement->bindValue(":$attribute",$this->{$attribute});
                     echo $this->{$attribute} ;
                }
                $statement->execute();
            }
            return $this->customFetchAll("select last_insert_id()");
           
        }
        public function saveByName($values){
            foreach($values as $tablename=>$attributes){
                var_dump($attributes);
                $tablerecords=array_keys($attributes);
                $params=array_map(fn($attr)=>$attributes[$attr],$tablerecords);
                $statement=self::prepare("Insert into $tablename (".implode(',',$tablerecords).") VALUES (".implode(',',$params).")");
                $statement->execute();
            }
            return $this->customFetchAll("select last_insert_id()");
        }
        //returns an object
        public function findOne($where){        //[email=>'dfdf',name=>'dfd]
            $tablename=static::tableName();
            $attributes=array_keys($where);
            $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("Select * from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return $statement->fetchObject(static::class);
        }
        //to get medicine panadol  call findByKeyword('name',"pana")
        public function findByKeyword($parameter,$keyword){
            $tablename=static::tableName();
            $statement=self::prepare("Select * from $tablename where $parameter like '%$keyword%'");
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        //returns an associative array $where should be like [patient_ID=>1]
        //returns only an associative array with one element
        public function fetchAssocOne($where){
            $tablename=static::tableName();
            $attributes=array_keys($where);
            $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("Select * from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);

        }
        //return associative array with all matching parameters
        public function fetchAssocAll($where=[]){        //[email=>'dfdf',name=>'dfd]
            $tablename=static::tableName();
            if($where){
                $attributes=array_keys($where);
                $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
                $statement=self::prepare("Select * from $tablename where $sql");
                foreach($where as $key=>$item){
                
                    $statement->bindValue(":$key",$item);
                }
            }
            //if where is empty where part of the query is empty
            else{
                $statement=self::prepare("Select * from $tablename");
            }
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        //table name should be given as the second parameter
        public function fetchAssocAllByName($where,$table){
            $tablename=$table;
            if($where){
                $attributes=array_keys($where);
                $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
                $statement=self::prepare("Select * from $tablename where $sql");
                foreach($where as $key=>$item){
                
                    $statement->bindValue(":$key",$item);
                }
            }
            else{
                $statement=self::prepare("Select * from $tablename");
            }
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        
        //execute a mysql query
        public function customFetchAll($query){
            $statement=self::prepare($query);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);

        }
        //delete data in a table      $where= ['patient_ID'=>'34']
        public function deleteRecord($where){
            $tablename=static::tableName();
            $attributes=array_keys($where);
            $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("delete from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return true;

        }
        //delete a data in a given table
        public function deleteRecordByName($where,$table){
            $tablename=$table;
            $attributes=array_keys($where);
            $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("delete from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return true;
        }
        //update data in a table
        public function updateRecord($where){
            $where_attributes=array_keys($where);
            $this->fileStore();
            $tablename=$this->tableName();
            $attributes=$this->attributes();
            $values=array_map(fn($attr)=>"$attr=:$attr",$attributes);
        // var_dump($values);
            $where=array_map(fn($attr)=>"$attr=$where[$attr]",$where_attributes);
            
            $statement=self::prepare("update $tablename set ".implode(',',$values)." where ".implode('AND',$where));
            foreach ($attributes as $attribute){
                $statement->bindValue(":$attribute",$this->{$attribute});
            }
            $statement->execute();
            
            return $this->customFetchAll("select last_insert_id()");
        }

        public function prepare($sql){
            return Application::$app->db->pdo->prepare($sql);
        }
        //updateRecord(['_order'=>['order_ID'=>12]],['_order'=>['name'=>'dfdf']])  -this will update the name as dfdf in the table order
        public function updateRecordv2($record_where,$values){
            $this->fileStore();
            foreach ($record_where as $tablename=>$where) {
                $value_attributes = array_keys($values[$tablename]); //['name']
                $where_attributes=array_keys($where);//['order_ID']
                $values = array_map(fn($attr) => "$attr=$values[$tablename][$attr]", $value_attributes);
                $where = array_map(fn($attr) => "$attr=$where[$attr]", $where_attributes);

                $statement = self::prepare("update $tablename set " . implode(',', $values) . " where " . implode('AND', $where));
                $statement->execute();
            }

            return $this->customFetchAll("select last_insert_id()");
        }
    }


?>