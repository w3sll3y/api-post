<?php

namespace DB;

use InvalidArgumentException;
use PDO;
use PDOException;
use \Util\GenericConstUtil;

class Mysql {
  private object $db;

  public function __construct() {
    $this->db = $this->setDB();
  }

  public function setDB() {
    try {
      return new PDO("mysql:host=" . HOST . "; dbname=" . DB . ";", USER, PASSWORD);
    } catch (PDOException $exception) {
      throw new PDOException($exception->getMessage());
    }
  }

  public function deleteById($table, $id) {
    try {
      $sqlDetele = 'DELETE FROM '. $table .' WHERE id = :id';
      
      if($table && $id) {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare($sqlDetele);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
          $this->db->commit();
          return GenericConstUtil::MSG_DELETADO_SUCESSO;
        }
        $this->db->rollBack();
        throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_SEM_RETORNO);
      }
    }catch (PDOException $exception){
      throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }
  }

  public function deleteToken($table, $token) {
    try {
      $sqlDetele = 'DELETE FROM '. $table .' WHERE token = :token';
      
      if($table && $token) {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare($sqlDetele);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
          $this->db->commit();
          return GenericConstUtil::MSG_DELETADO_SUCESSO;
        }
        $this->db->rollBack();
        throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_SEM_RETORNO);
      }
    }catch (PDOException $exception){
      throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }
  }

  public function getAll($table) {
    if($table) {
      $sqlConsult = 'SELECT * FROM ' . $table;
      $stmt = $this->db->query($sqlConsult);
      $data = $stmt->fetchAll($this->db::FETCH_ASSOC);
      if(is_array($data) && count($data) > 0) {
        return $data;
      }
    }
    throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_SEM_RETORNO);
  }

  public function getById($table, $id) {
    if($table && $id) { 
      $sqlConsult = 'SELECT * FROM '. $table . ' WHERE id = :id';
      $stmt = $this->db->prepare($sqlConsult);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $dataTotal = $stmt->rowCount();
      if($dataTotal == 1) {
        return $stmt->fetch($this->db::FETCH_ASSOC);
      }
      throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_SEM_RETORNO);
    }
    throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_ID_OBRIGATORIO);
  }

  public function getByIdComment($table, $id) {
    if($table && $id) { 
      $sqlConsult = 'SELECT * FROM '. $table . ' WHERE postId = :id';
      $stmt = $this->db->prepare($sqlConsult);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $dataTotal = $stmt->rowCount();
      if($dataTotal > 0) {
        return $stmt->fetchAll($this->db::FETCH_ASSOC);
      }
      throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_SEM_RETORNO);
    }
    throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_ID_OBRIGATORIO);
  }
  public function getDB() {
    return $this->db;
  }
}