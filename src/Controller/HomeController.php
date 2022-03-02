<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\readIssues;

class HomeController extends AbstractController
{
    /**
     * @Route("/home")
     */

    public function showIssues(): Response
    {
        $databasePath = __DIR__."/../../var/issues.json";
        $issues = readIssues($databasePath);

        dump($issues);
        return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues]));
    }
}
