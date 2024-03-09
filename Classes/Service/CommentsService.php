<?php

namespace Service;

use Generator;
use \Repository\UserRepository;
use \Repository\CommentsRepository;
use Util\GenericConstUtil;

class CommentsService {
  public const TABLE = "comments";
  public const RECURSE_GET = ['listar'];
  public const RECURSE_DELETE = ['deletar'];
  public const RECURSE_POST = ['cadastrar'];
  public const RECURSE_PUT = ['atualizar'];
  private array $data;

  private array $dataBodyRequest = [];
  public object $UserRepoistory;
  public object $CommentsRepository;

  public function __construct($data = []) {
    $this->data = $data;
    $this->UserRepoistory = new UserRepository();
    $this->CommentsRepository = new CommentsRepository();
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
    return $this->UserRepoistory->getMySQL()->getByIdComment(self::TABLE, $this->data['id']);
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
  
  public function setDataBodyReq($dataRequest) {
    $this->dataBodyRequest = $dataRequest;
  }

  public function validatePost() {
    $retorno = null;
    $recourse = $this->data['recurso'];

    if(in_array($recourse, self::RECURSE_POST, true)) {
      $retorno = $this->$recourse();
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

  private function cadastrar(){
    [$createdBy, $content, $createdAt, $postId] = [$this->dataBodyRequest['createdBy'], $this->dataBodyRequest['content'], $this->dataBodyRequest['createdAt'], $this->dataBodyRequest['postId']];
    if($createdBy &&  $content &&  $createdAt && $postId) {

      if($this->CommentsRepository->insertComment($createdBy, $content, $createdAt, $postId) > 0) {
        $idInserted = $this->CommentsRepository->getMySQL()->getDB()->lastInsertId();
        $this->CommentsRepository->getMySQL()->getDB()->commit();
        return ['id-inserido' => $idInserted];
      }

      $this->CommentsRepository->getMySQL->getDB()->rollback();
      
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }
    throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
  }
  private function atualizar() {
    if ($this->UserRepoistory->updateUser($this->data['id'], $this->dataBodyRequest) > 0) {
      $this->UserRepoistory->getMySQL()->getDB()->commit();
      return GenericConstUtil::MSG_ATUALIZADO_SUCESSO;
    }

    $this->UserRepoistory->getMySQL()->getDB()->rollback();
    throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_NAO_AFETADO);
  }
}