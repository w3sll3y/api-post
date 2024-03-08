<?php

namespace Service;

use \Repository\LoginRepository;
use Util\GenericConstUtil;

use Firebase\JWT\JWT;

class LoginService {
  public const TABLE = "users";
  public const TABLETOKEN = "tokens_auth";
  public const RECURSE_GET = ['listar'];
  public const RECURSE_LOGOUT = ['logout'];
  public const RECURSE_POST = ['login'];
  public const RECURSE_PUT = ['atualizar'];
  private array $data;

  private array $dataBodyRequest = [];
  public object $PostRepository;
  public object $LoginRepository;

  public function __construct($data = []) {
    $this->data = $data;
    $this->LoginRepository = new LoginRepository(); // Inicialize aqui
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
    [$token] = [$this->dataBodyRequest['token']];
    if(in_array($recourse, self::RECURSE_LOGOUT, true)) {
      if($token > 0) {
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

  private function logout(){
    return $this->LoginRepository->getMySQL()->deleteToken(self::TABLETOKEN, $this->dataBodyRequest['token']);
  }

  private function generateJWTToken($dataUser) {
        $secretKey = "F4E3B1831CB543CE7D58E22361B5CEE67D4A1E0AE9987AA66C13C8687FABE926"; 
        $exp = 31536000;  

        $tokenPayload = [
            "id" => $dataUser[0]['id'],
            "nome" => $dataUser[0]['nome'],
            "login" => $dataUser[0]['login'],
            "exp" => $exp,
        ];
        $token = JWT::encode($tokenPayload, $secretKey, 'HS256');
        return $token;
    }
  private function login(){
    $consultInsert = 'INSERT INTO tokens_auth (token, status) VALUES (:token, \'S\')';
    [$login, $senha] = [$this->dataBodyRequest['login'], $this->dataBodyRequest['senha']];
    if($login &&  $senha) {
      $dataUser = $this->LoginRepository->insertData($login, $senha);
      if($dataUser) {
        $jwt = $this->generateJWTToken($dataUser);
  
        $this->LoginRepository->getMySQL()->getDB()->commit();
        $stmt = $this->LoginRepository->getMySQL()->getDB()->prepare($consultInsert);
        $stmt->bindValue(':token', $jwt);
        $stmt->execute();
        $returnData = [$jwt, $dataUser];
        return $returnData;
      }
      else {
        header('HTTP/1.1 401 Unauthorized');
        throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
      }
    } else {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }
  }

  private function loggout(){
    $consultInsert = 'INSERT INTO tokens_auth (token, status) VALUES (:token, \'S\')';
    [$login, $senha] = [$this->dataBodyRequest['login'], $this->dataBodyRequest['senha']];
    if($login &&  $senha) {
      $dataUser = $this->LoginRepository->insertData($login, $senha);
      if($dataUser) {
        $jwt = $this->generateJWTToken($dataUser);
  
        $this->LoginRepository->getMySQL()->getDB()->commit();
        $stmt = $this->LoginRepository->getMySQL()->getDB()->prepare($consultInsert);
        $stmt->bindValue(':token', $jwt);
        $stmt->execute();
        $returnData = [$jwt, $dataUser];
        return $returnData;
      }
      else {
        header('HTTP/1.1 401 Unauthorized');
        throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
      }
    } else {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }
  }

}