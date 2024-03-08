<?php

require_once __DIR__ . '/vendor/autoload.php';

use Util\GenericConstUtil;
use Validator\RequestValidator;
use Util\RoutesUtil;

include 'bootstrap.php';

try {
  $RequestValidator = new RequestValidator(RoutesUtil::getRoutes());
  $retorno = $RequestValidator->processRequest();

  $JsonUtil = new \Util\JsonUtil();
  $JsonUtil->ProccessArrayReturn($retorno);

} catch (Exception $exception) {
  echo json_encode([
    GenericConstUtil::TIPO => GenericConstUtil::TIPO_ERRO,
    GenericConstUtil::RESPOSTA => mb_convert_encoding($exception->getMessage(), 'UTF-8')
  ]);
  exit;
}