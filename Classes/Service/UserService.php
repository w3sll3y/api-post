<?php

namespace Service;

use \Repository\UserRepository;
use Util\GenericConstUtil;

class UserService {
  public const TABLE = "users";
  public const RECURSE_GET = ['listar'];
  public const RECURSE_DELETE = ['deletar'];
  private array $data;

  public object $UserRepoistory;

  public function __construct($data = []) {
    $this->data = $data;
    $this->UserRepoistory = new UserRepository();
  }

  public function validateGet() {
    $retorno = null;
    $recourse = $this->data['recurso'];
    if(in_array($recourse, self::RECURSE_GET, true)) {
      $retorno = $this->data['id'] > 0 ? $this->getById() : $this->$recourse();
    } else {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
    }

    if($retorno === null) {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }

    return $retorno;
  }

  private function getById() {
    return $this->UserRepoistory->getMySQL()->getById(self::TABLE, $this->data['id']);
  }

  private function listar() {
    return $this->UserRepoistory->getMySQL()->getAll(self::TABLE);
  }
  public function validateDelete() {
    $retorno = null;
    $recourse = $this->data['recurso'];
    
    
    if(in_array($recourse, self::RECURSE_DELETE, true)) {
      if($this->data['id'] > 0) {
        $retorno = $this->$recourse();   
      } else {
        throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_ID_OBRIGATORIO);
      }
    } else {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
    }


    if($retorno === null) {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }

    return $retorno;
  }

  private function deletar(){
    return $this->UserRepoistory->getMySQL()->deleteById(self::TABLE, $this->data['id']);
  }

}