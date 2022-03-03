<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\displayIssue;

class DetailsController extends AbstractController
{
    /**
     * @Route("/issues/{id}")
     */
    public function showDetails($id): Response
    {
        $databasePath = __DIR__."/../../var/issues.json";
        $issue = displayIssue($id, $databasePath);

        dump($databasePath);

        if ($issue == null) {

            //a eviter
            return new Response($this->renderView("issues/error.html.twig", ["id" => (int)$id], 404));
        }
        return new Response($this->renderView("issues/details.html.twig", ["issue" => $issue, "id" => $id + 1]));
    }
}
