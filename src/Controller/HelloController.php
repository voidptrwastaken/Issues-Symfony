<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{name}")
     */

    public function sayHello(string $name): Response
    {
        return new Response($this->renderView("hello.html.twig", ["name" => $name]));
    }
}
