<?php

namespace Service;

use \Repository\PostRepository;
use Util\GenericConstUtil;

class PostService {
  public const TABLE = "posts";
  public const RECURSE_GET = ['listar'];
  public const RECURSE_DELETE = ['deletar'];
  public const RECURSE_POST = ['cadastrar'];
  public const RECURSE_PUT = ['atualizar'];
  public const RECURSE_PUT_LIKE = ['like'];
  private array $data;

  private array $dataBodyRequest = [];
  public object $PostRepository;

  public function __construct($data = []) {
    $this->data = $data;
    $this->PostRepository = new PostRepository();
  }

  public function validateGetPost() {
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
    return $this->PostRepository->getMySQL()->getById(self::TABLE, $this->data['id']);
  }

  private function listar() {
    return $this->PostRepository->getMySQL()->getAll(self::TABLE);
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
    return $this->PostRepository->getMySQL()->deleteById(self::TABLE, $this->data['id']);
  }

  private function cadastrar(){
    [$idAuthor, $type, $title, $description, $createdAt, $typeMachine] = [
      $this->dataBodyRequest['idAuthor'], 
      $this->dataBodyRequest['type'], 
      $this->dataBodyRequest['title'],
      $this->dataBodyRequest['description'],
      $this->dataBodyRequest['createdAt'],
      $this->dataBodyRequest['typeMachine'],
    ];
    if($idAuthor &&  $type &&  $title && $description && $createdAt && $typeMachine) {
      if($this->PostRepository->insertPost($idAuthor, $type, $title, $description, $createdAt, $typeMachine) > 0) {
        $idInserted = $this->PostRepository->getMySQL()->getDB()->lastInsertId();
        $this->PostRepository->getMySQL()->getDB()->commit();
        return ['id-inserido' => $idInserted];
      }

      $this->PostRepository->getMySQL->getDB()->rollback();
      
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_GENERICO);
    }
    throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
  }

    public function validatePut() {
    $retorno = null;
    $recourse = $this->data['recurso'];
  
    if(in_array($recourse, self::RECURSE_PUT, true)) {
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

  public function validatePutLiked() {
    $retorno = null;
    $recourse = $this->data['recurso'];
  
    if(in_array($recourse, self::RECURSE_PUT_LIKE, true)) {
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

  private function atualizar() {
    if ($this->PostRepository->updatePost($this->data['id'], $this->dataBodyRequest) > 0) {
      $this->PostRepository->getMySQL()->getDB()->commit();
      return GenericConstUtil::MSG_ATUALIZADO_SUCESSO;
    }

    $this->PostRepository->getMySQL()->getDB()->rollback();
    throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_NAO_AFETADO);
  }

  private function like() {
    if ($this->PostRepository->updatePostByLike($this->data['id'], $this->dataBodyRequest) > 0) {
      $this->PostRepository->getMySQL()->getDB()->commit();
      return GenericConstUtil::MSG_ATUALIZADO_SUCESSO;
    }

    $this->PostRepository->getMySQL()->getDB()->rollback();
    throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_NAO_AFETADO);
  }
}