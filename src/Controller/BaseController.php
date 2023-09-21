<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractFOSRestController
{
    public function sendJson(array $data): Response
    {
        return $this->handleView($this->view($data, array_key_exists('data', $data) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
    }
}
