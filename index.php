<?php

require_once __DIR__ . '/controller/FuncionarioController.php';
require_once __DIR__ . '/config/utils.php';

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

$funcionarioController = new FuncionarioController();

$basePath = '/resturante-webservice';
$relativeUri = str_replace($basePath, '', $requestUri);
$uriSegments = explode('/', trim($relativeUri, '/'));

$resource = $uriSegments[0] ?? '';
$id = $uriSegments[1] ?? null;

switch ($resource) {
    case 'funcionario':
        switch ($method) {
            case 'GET':
                if ($id) {
                    $funcionarioController->listar($id);
                } else {
                    $funcionarioController->listar();
                }
                break;

            case 'POST':
                $funcionarioController->criar($data);
                break;

            case 'PUT':
                if ($id) {
                    $funcionarioController->atualizar($id, $data);
                } else {
                    jsonResponse(400, ["status" => "error", "message" => "ID necessário para atualização"]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $funcionarioController->deletar($id);
                } else {
                    jsonResponse(400, ["status" => "error", "message" => "ID necessário para exclusão"]);
                }
                break;

            default:
                jsonResponse(405, ["status" => "error", "message" => "Método não permitido"]);
        }
        break;

    default:
        jsonResponse(404, ["status" => "error", "message" => "Recurso não encontrado"]);
}
