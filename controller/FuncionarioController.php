<?php

require_once __DIR__ . '/../model/Funcionario.php';
require_once __DIR__ . '/../config/utils.php';

class FuncionarioController {

    public function listar($id = null): void
    {
        try {
            if ($id) {
                if (!Funcionario::exist($id)) {
                    throw new Exception("Funcionário não encontrado", 404);
                }
                $funcionario = Funcionario::getById($id);
                jsonResponse(200, ["status" => "success", "data" => $funcionario]);
            } else {
                $funcionarios = Funcionario::listar();
                jsonResponse(200, ["status" => "success", "data" => $funcionarios]);
            }
        } catch (Exception $e) {
            jsonResponse($e->getCode() ?: 500, ["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function criar($data): void
    {
        try {
            if (!valid($data, ["nome", "username", "senha"])) {
                throw new Exception("Nome, username e/ou senha não encontrados", 400);
            }

            if (Funcionario::existsByUsername($data["username"])) {
                throw new Exception("O username já existe. Tente outro.", 409);
            }

            $insertedId = Funcionario::cadastrar($data["nome"], $data["username"], $data["senha"]);
            jsonResponse(201, ["status" => "success", "data" => ["id" => $insertedId]]);
        } catch (Exception $e) {
            jsonResponse($e->getCode() ?: 500, ["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function atualizar($id, $data): void
    {
        try {
            if (!valid($data, ["nome", "username"])) {
                throw new Exception("Nome e/ou username não encontrados", 400);
            }

            if (!Funcionario::exist($id)) {
                throw new Exception("Funcionário não encontrado", 404);
            }

            $funcionarioExistente = Funcionario::getByUsername($data["username"]);
            if ($funcionarioExistente && $funcionarioExistente['id'] != $id) {
                throw new Exception("O username já existe. Tente outro.", 409);
            }

            $res = Funcionario::atualizar($id, $data["nome"], $data["username"]);
            jsonResponse(200, ["status" => "success", "data" => ["id" => $id]]);
        } catch (Exception $e) {
            jsonResponse($e->getCode() ?: 500, ["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function deletar($id): void
    {
        try {
            if (!Funcionario::exist($id)) {
                throw new Exception("Funcionário não encontrado", 404);
            }

            Funcionario::deleteById($id);
            jsonResponse(200, ["status" => "success", "data" => ["id" => $id]]);
        } catch (Exception $e) {
            jsonResponse($e->getCode() ?: 500, ["status" => "error", "message" => $e->getMessage()]);
        }
    }
}