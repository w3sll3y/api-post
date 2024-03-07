<?php

namespace Validator;

use Repository\TokensAuthRepo;
use Service\UserService;
use Util\GenericConstUtil;
use Util\JsonUtil;

class RequestValidator { 

  private $request;
  private array $dataRequest;
  private object $TokensAuthRepo; 

  const GET = 'GET';
  const DELETE = 'DELETE';
  const USUARIOS = 'USUARIOS';
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
    $this->TokensAuthRepo->validToken(getallheaders()['Authorization']);
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
        default: 
          throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_RECURSO_INEXISTENTE);
      }
    }
    return $retorno;
  }
}