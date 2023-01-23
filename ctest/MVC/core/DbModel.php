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

        public function findOne($where){        //[email=>'dfdf',name=>'dfd]
            $tablename=static::tableName();
            $attributes=array_keys($where);
            $sql=implode("AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("Select * from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return $statement->fetchObject(static::class);
        }
        public function findAll($where=[]){        //[email=>'dfdf',name=>'dfd]
            $tablename=static::tableName();
            if($where){
                $attributes=array_keys($where);
                $sql=implode("AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
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
        

        public function customFetchAll($query){
            $statement=self::prepare($query);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);

        }
        public function deleteRecord($where){
            $tablename=static::tableName();
            $attributes=array_keys($where);
            $sql=implode("AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));
            $statement=self::prepare("delete from $tablename where $sql");
            foreach($where as $key=>$item){
            
                $statement->bindValue(":$key",$item);
            }
            $statement->execute();
            return true;

        }
        public function updateRecord($where){
          
            // var_dump($files);
            // $tablename=static::tableName();
             $where_attributes=array_keys($where);
            // $value_attributes=array_keys($values);
            // $file_attributes=array_keys($files);
             //$where=implode("AND ",array_map(fn($attr)=>"$attr=$where[$attr]",$where_attributes));
            // $file=implode(", ",array_map(fn($attr)=>"$attr="."'".uniqid().$files[$attr]['name']."'",$file_attributes));
            // $value=implode(", ",array_map(fn($attr)=>"$attr="."'".$values[$attr]."'",$value_attributes));
            // echo "update $tablename set $value"." ,$file where $where";
            // $this->customFetchAll("update $tablename set $value"." ,$file where $where");
           

            // return true;
            $this->fileStore();
            $tablename=$this->tableName();
            $attributes=$this->attributes();
            $values=array_map(fn($attr)=>"$attr=:$attr",$attributes);
            $where=array_map(fn($attr)=>"$attr=$where[$attr]",$where_attributes);
            
            $statement=self::prepare("update $tablename set ".implode(',',$values)." where ".implode('AND',$where));
            foreach ($attributes as $attribute){
                $statement->bindValue(":$attribute",$this->{$attribute});
            }
            // var_dump($statement);
            $statement->execute();
            
            return $this->customFetchAll("select last_insert_id()");
        }
        public function prepare($sql){
            return Application::$app->db->pdo->prepare($sql);
        }
    }


?>