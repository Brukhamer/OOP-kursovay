<?php

namespace app\core;

use Couchbase\ParsingFailureException;
use PDO;
use PDOException;

abstract class BaseModel
{
    protected $db;

    public function __construct()
    {
        $config = require 'app/config/db.php';
        try {
            $this->db = new PDO(
                $config['provider'].':host='.$config['hostname'].';dbname='.$config['database'],
                $config['username'],
                $config['password']
            );

        } catch (PDOException $ex){
            print 'Ошибка'. $ex->getMessage().'<br>';
            die();

        }
    }

    public function query($sql, $params = [] ){
        $query = $this->db->prepare($sql);
        if(!empty($params)){
            foreach ($params as $key=>$value){
                $query->bindValue(':'.$key, $value);
            }
        }
        $query->execute();
        return $query;
    }

    protected function select($sql, $params = []){
        $result = $this->query($sql, $params);
        return $result->fetchALL(PDO::FETCH_ASSOC);
    }

    protected function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return(int)$this->db->LastInsertId() ;
    }

    protected function update($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        $query->rowCount();
    }
    protected function delete($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        $query->rowCount();
    }

}