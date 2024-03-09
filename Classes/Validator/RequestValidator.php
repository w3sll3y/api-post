<?php

namespace Validator;

use Repository\TokensAuthRepo;
use Service\UserService;
use Service\PostService;
use Service\LoginService;
use Service\CommentsService;
use Util\GenericConstUtil;
use Util\JsonUtil;

class RequestValidator { 

  private $request;
  private array $dataRequest = [];
  private object $TokensAuthRepo; 

  const GET = 'GET';
  const DELETE = 'DELETE';
  const USUARIOS = 'USUARIOS';
  const POSTS = 'POSTS';
  const COMENTARIO = 'COMENTARIO';
  const LOGIN = 'LOGIN';
  public function __construct($request) {
    $this->request = $request;
    $this->TokensAuthRepo = new TokensAuthRepo();
  }

  public function processRequest() {
    $retorno = mb_convert_encoding(GenericConstUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
    if(in_array($this->request['metodo'], GenericConstUtil::TIPO_REQUEST, true)) {
      $retorno = $this->directRequest();
    };
    return $retorno;
  }

  private function directRequest() {
    if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE) {
      $this->dataRequest = JsonUtil::BodyJsonReq();
    } 
    if($this->request['rota'] === self::POSTS || $this->request['rota'] === self::POSTS) {
      $this->TokensAuthRepo->validToken(getallheaders()['authorization']);
    }
    $method = $this->request['metodo'];
    return $this->$method();
  }

  private function get() {
    $retorno = mb_convert_encoding(GenericConstUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
    if (in_array($this->request['rota'], GenericConstUtil::TIPO_GET, true)) {
      switch($this->request['rota']) {
        case self::USUARIOS:
          $UserService = new UserService($this->request);
          $retorno = $UserService->validateGet();
          break;
        case self::POSTS:
            $PostService = new PostService($this->request);
            $retorno = $PostService->validateGetPost();
            break;
        case self::COMENTARIO:
          $CommentsService = new CommentsService($this->request);
          $retorno = $CommentsService->validateGet();
          break;
        default: 
          throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
      }
    }
    return $retorno;
  }

  private function delete() {
    $retorno = mb_convert_encoding(GenericConstUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
    
    if (in_array($this->request['rota'], GenericConstUtil::TIPO_DELETE, true)) {
      switch($this->request['rota']) {
        case self::USUARIOS:
          $UserService = new UserService($this->request);
          $retorno = $UserService->validateDelete();
          break;
        case self::POSTS:
          $PostService = new PostService($this->request);
          $retorno = $PostService->validateDelete();
          break;
        default: 
          throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
      }
    }
    return $retorno;
  }

  private function post() {
    $retorno = mb_convert_encoding(GenericConstUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
    
    if (in_array($this->request['rota'], GenericConstUtil::TIPO_POST, true)) {
      switch($this->request['rota']) {
        case self::USUARIOS:
          $UserService = new UserService($this->request);
          $UserService->setDataBodyReq($this->dataRequest);
          $retorno = $UserService->validatePost();
          break;
        case self::POSTS:
          $PostService = new PostService($this->request);
          $PostService->setDataBodyReq($this->dataRequest);
          $retorno = $PostService->validatePost();
          break;
        case self::LOGIN:
          if($this->request['recurso'] === 'login') {
            $LoginService = new LoginService($this->request);
            $LoginService->setDataBodyReq($this->dataRequest);
            $retorno = $LoginService->validatePost();
          }
          if($this->request['recurso'] === 'logout') {
            $LoginService = new LoginService($this->request);
            $LoginService->setDataBodyReq($this->dataRequest);
            $retorno = $LoginService->validateDelete();
          }
          break;  
        case self::COMENTARIO:
          $CommentsService = new CommentsService($this->request);
          $CommentsService->setDataBodyReq($this->dataRequest);
          $retorno = $CommentsService->validatePost();
          break;
        default: 
          throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
      }
    }
    return $retorno;
  }

  private function put() {
    $retorno = mb_convert_encoding(GenericConstUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
    
    if (in_array($this->request['rota'], GenericConstUtil::TIPO_PUT, true)) {
      switch($this->request['rota']) {
        case self::USUARIOS:
          $UserService = new UserService($this->request);
          $UserService->setDataBodyReq($this->dataRequest);
          $retorno = $UserService->validatePut();
          break;
        case self::POSTS:
          if($this->request['recurso'] === 'like') { 
            $PostService = new PostService($this->request);
            $PostService->setDataBodyReq($this->dataRequest);
            $retorno = $PostService->validatePutLiked();
          } if($this->request['recurso'] === 'atualizar') {
            $PostService = new PostService($this->request);
            $PostService->setDataBodyReq($this->dataRequest);
            $retorno = $PostService->validatePut();
          }
          break;
        default: 
          throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
      }
    }
    return $retorno;
  }

}
