<?php
/**
 *
 * https://opensource.org/licenses/MIT The MIT License (MIT)
 *
 * @package    JattDB
 * @author     Gursharanjit Singh <g@hayer.me>
 * @copyright  2016
 * @license    https://opensource.org/licenses/MIT The MIT License (MIT)
 * @version    1.0.1
 * @link
 */
 namespace JattDB;
 class JattPDO{
   private $data = array();
   protected $db;
   protected $statement;
   protected $error;
   public function __construct($params = array()){
     $dbs = "mysql:host=" . $params['host'] . ";dbname=" . $params['name'];
     			try {
     			$options = array(
     						\PDO::ATTR_PERSISTENT => true,
     						\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
     					);
     			$this->db = new \PDO($dbs, $params['user'] , $params['password'],$options);
        } catch(\PDOException $e) {
     				die ('Database Connection Error');
     			}
          $this->db->exec("SET NAMES 'utf8'");
          $this->db->exec("SET CHARACTER SET utf8");
          $this->db->exec("SET CHARACTER_SET_CONNECTION=utf8");
          $this->db->exec("SET SQL_MODE = ''");

   }

   public function disconnect(){
     $this->db = null;
   }

   function __set($var,$val){
     $this->data[$var] = $val;
   }

   function __get($var){
     return  (isset($this->data[$var]))?$this->data[$var]:null;
   }

   public function escape($value) {
     return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
   }

  public function query($query){
    $this->statement = $this->db->prepare($query);
  }

 public function bind($param, $value, $type = null){

   if (is_null($type)) {
     switch (true) {
     case is_int($value):
       $type = PDO::PARAM_INT;
       break;
     case is_bool($value):
        $value = $this->escape($value);
        $type = PDO::PARAM_BOOL;
       break;
     case is_null($value):
       $type = PDO::PARAM_NULL;
       break;
     default:
     $value = $this->escape($value);
     $type = PDO::PARAM_STR;
     }
   }
   $this->statement->bindValue($param, $value, $type);
 }

 public function bindAll($values = array()){
   foreach($values as $key => $value){
      $this->bind($key,$value);
   }
 }

 public function execute(){
   return $this->statement->execute();
 }

 public function rows(){
   $this->execute();
   return $this->statement->fetchAll(PDO::FETCH_ASSOC);
 }

 public function row(){
   $this->execute();
   return $this->statement->fetch(PDO::FETCH_ASSOC);
 }

 public function rowCount(){
   return $this->statement->rowCount();
 }

 public function insertId(){
   return $this->db->lastInsertId();
 }

 public function beginTransaction(){
   return $this->db->beginTransaction();
 }

 public function endTransaction(){
   return $this->db->commit();
 }

 public function cancelTransaction(){
   return $this->db->rollBack();
 }

 public function debugDumpParams(){
   return $this->statement->debugDumpParams();
 }

 }
