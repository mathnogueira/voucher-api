<?php

namespace App\Controllers;

use Slim\Http\Response;
use App\Exceptions\InvalidModelException;
use App\Exceptions\ModelConflictException;
use App\Exceptions\ModelNotFoundException;

class ControllerActionExecuter
{
    private $controller;

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function execute(\Closure $action, Response $response)
    {
        try {
            return $action->call($this->controller);
        } catch (InvalidModelException $ex) {
            return $response->withJson(['Errors' => $ex->getErrors()], 400);
        } catch (ModelConflictException $ex) {
            return $response->withJson(['Errors' => [$ex->getMessage()]], 409);
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        } catch (\Exception $ex) {
            return $response->withJson(['Errors' => ['An error occurred on the server']], 500);
        }
    }
}