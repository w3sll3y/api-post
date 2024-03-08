<?php

namespace Repository;

use \DB\MySQL;

class LoginRepository {
  private object $MySQL;
  public const TABLE = 'users';

  public function __construct() {
    $this->MySQL = new MySQL();
  }

  public function getMySQL() {
    return $this->MySQL;
  }

  public function insertData($login, $senha) {
    $consultInsert = 'SELECT * FROM ' . self::TABLE . ' WHERE login = :login AND senha = :senha';

    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultInsert);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    return $stmt->fetchAll($this->MySQL->getDB()::FETCH_ASSOC);
  }
}