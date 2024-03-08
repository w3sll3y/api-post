<?php

namespace Repository;

use \DB\MySQL;

class PostRepository {
  private object $MySQL;
  public const TABLE = 'posts';

  public function __construct() {
    $this->MySQL = new MySQL();
  }

  public function getMySQL() {
    return $this->MySQL;
  }

  public function insertPost($idAuthor, $type, $title, $description, $createdAt, $typeMachine) {
    $consultIsert = 'INSERT INTO ' . self::TABLE . ' (idAuthor, type, title, description, createdAt, typeMachine) VALUES (:idAuthor, :type, :title, :description, :createdAt, :typeMachine )';

    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultIsert);
    $stmt->bindParam(':idAuthor', $idAuthor);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':createdAt', $createdAt);
    $stmt->bindParam(':typeMachine', $typeMachine);
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function updatePost($id, $data) {
    $consultUpdate = 'UPDATE ' . self::TABLE . ' SET idAuthor = :idAuthor, type = :type, title = :title, description = :description, createdAt = :createdAt, typeMachine = :typeMachine WHERE id = :id';
    

    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultUpdate);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':idAuthor', $data['idAuthor']);
    $stmt->bindParam(':type', $data['type']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':createdAt', $data['createdAt']);
    $stmt->bindParam(':typeMachine', $data['typeMachine']);
    $stmt->execute();
    return $stmt->rowCount();
  }
}