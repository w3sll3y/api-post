<?php

namespace Repository;

use \DB\MySQL;

class CommentsRepository {
  private object $MySQL;
  public const TABLE = 'comments';

  public function __construct() {
    $this->MySQL = new MySQL();
  }

  public function getMySQL() {
    return $this->MySQL;
  }

  public function insertComment($createdBy, $content, $createdAt, $postId) {
    $consultIsert = 'INSERT INTO ' . self::TABLE . ' (createdBy, content, createdAt, postId) VALUES (:createdBy, :content, :createdAt, :postId)';
    $this->MySQL->getDB()->beginTransaction();
    $stmt = $this->MySQL->getDB()->prepare($consultIsert);
    $stmt->bindParam(':createdBy', $createdBy);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':createdAt', $createdAt);
    $stmt->bindParam(':postId', $postId);
    $stmt->execute();
    return $stmt->rowCount();
  }
}