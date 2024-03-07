<?php

namespace Repository;

use \DB\MySQL;

class UserRepository {
  private object $MySQL;
  public const TABLE = 'users';

  public function __construct() {
    $this->MySQL = new MySQL();
  }

  public function getMySQL() {
    return $this->MySQL;
  }

  public function insertUser($nome, $login, $senha) {
    $consultIsert = 'INSERT INTO ' . self::TABLE . ' (nome, login, senha) VALUES (:nome, :login, :senha)';

    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultIsert);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function updateUser($id, $data) {
    $consultUpdate = 'UPDATE ' . self::TABLE . ' SET nome = :nome, login = :login, senha = :senha WHERE id = :id';
    

    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultUpdate);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $data['nome']);
    $stmt->bindParam(':login', $data['login']);
    $stmt->bindParam(':senha', $data['senha']);
    $stmt->execute();
    return $stmt->rowCount();
  }
}