<?php

namespace Util;

use JsonException as JsonExceptionAlias;

class JsonUtil {
  public static function BodyJsonReq() {
    try {
      $postJson = json_decode(file_get_contents('php://input'), true);
    } catch (JsonExceptionAlias $exception) {
      throw new \InvalidArgumentException(GenericConstUtil::MSG_ERR0_JSON_VAZIO);
    }

    if(is_array($postJson) && count($postJson) > 0) { 
      return $postJson;
    }
  }

  public static function ProccessArrayReturn($data) {
    $dados = [];
    $dados[GenericConstUtil::TIPO] = GenericConstUtil::TIPO_ERRO;

    if(is_array($data) && count($data) > 0 || strlen($data) > 10) {
      $dados[GenericConstUtil::TIPO] = GenericConstUtil::TIPO_SUCESSO;
      $dados[GenericConstUtil::RESPOSTA] = $data;
    }
    self::returnJson($dados);
  }
  private static function returnJson($data) {
    header('Content-Type: application-json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    echo json_encode($data);
    exit;
  }
}