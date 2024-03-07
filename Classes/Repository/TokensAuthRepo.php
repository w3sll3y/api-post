<?php

namespace Repository;

use \DB\MySQL;
use Util\GenericConstUtil;
use InvalidArgumentException;

class TokensAuthRepo {
  private object $MySQL;
  public const TABLE = 'tokens_auth';

  public function __construct() {
    $this->MySQL = new MySQL();
  }

  public function validToken($token) {
    $token = str_replace([' ','Bearer'], '', $token);

    if($token) {
      $sqlConsult = 'SELECT id FROM `' . self::TABLE . '` WHERE token = :token AND status = :status';

      $stmt = $this->getMySQL()->getDB()->prepare($sqlConsult);
      $stmt->bindValue(':token', $token);
      $stmt->bindValue(':status', GenericConstUtil::SIM);
      $stmt->execute();

      if($stmt ->rowCount() !== 1) {
        header('HTTP/1.1 401 Unauthorized');
        throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
      }

    } else {
      throw new InvalidArgumentException(GenericConstUtil::MSG_ERRO_TOKEN_VAZIO);
    }
}

  public function getMySQL() {
    return $this->MySQL;
  }
}