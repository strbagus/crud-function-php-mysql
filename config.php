<?php 
class Config{
    private $host = "localhost";
    private $username = "root";
    private $password = "aditya9123";
    private $database = "pwpb_laundry";
    private $conn;
    private $result = array();

    // koneksi database
    public function __construct(){
        $this->conn = new mysqli($this->host,$this->username,$this->password,$this->database);
        if (mysqli_connect_error()) {
            trigger_error("Koneksi gagal : " .mysqli_connect_error());
        }else{
            return $this->conn;
        }
    }

    // cek table
    private function tableExists($table){
		$tablesInDb = $this->conn->query('SHOW TABLES FROM '.$this->database.' LIKE "'.$table.'"');
        if($tablesInDb){
        	if($tablesInDb->num_rows == 1){
                return true; 
            }else{
            	array_push($this->result,$table." tidak ada di database");
                return false; 
            }
        }
    }

    // select semua di dalam table
    public function read($table){
        if ($this->tableExists($table)) {
            $query = "SELECT * FROM ".$table;
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                foreach($result as $row){
                    $data[] = $row;
                }
                    return $data;
            }else{
                echo "Tidak Ada Data";
            }
        }else {
            return false;
        }
        
    }

    // select kolom dari id
    public function readId($table, $where){
        if($this->tableExists($table)){
            $query = 'SELECT * FROM '.$table.' WHERE '.$where;
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                foreach($result as $row){
                    $data[] = $row;
                }
                    return $data;
            }else{
                echo "Tidak Ada Data";
            }
        }else {
            return false;
        }
    }

    // create data ke table
    public function create($table, $inputs = array()){
        if ($this->tableExists($table)) {
            $query = 'INSERT INTO '.$table.' (`'.implode('`, `',array_keys($inputs)).'`) VALUES ("' . implode('", "', $inputs) . '")';
            var_dump($query);
            if ($insert = $this->conn->query($query)) {

                return true;
            }else{
                array_push($this->result,$this->conn->error);
                return false;
            }
        }else{
            return false;
        }
    }

    // update
    public function update($table, $inputs=array(), $where){
        if ($this->tableExists($table)) {
            foreach($inputs as $key=>$value){
                $data[]=$key.'="'.$value.'"';
            }
            $query = 'UPDATE '.$table.' SET '.implode(', ',$data).' WHERE '.$where;
            if ($a = $this->conn->query($query)) {
                
                return true;
            }else{
                array_push($this->result,$this->conn->error);
                return false;
            }

        }
    }

    // delete 
    public function delete($table, $where = null){
        if ($this->tableExists($table)) {
            $query = 'DELETE FROM '.$table.' WHERE '.$where;

            if ($a = $this->conn->query($query)) {
                return true;
            }else {
                return false;
            }
        }else{
            return false;
        }
    }

    // sesuai dengan SQL statement
    public function escapeString($data){
        return $this->conn->real_escape_string($data);
    }

    

}