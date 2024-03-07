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
}