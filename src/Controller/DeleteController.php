<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\displayIssue;

class DeleteController extends AbstractController
{
    /**
     * @Route("/issues/delete/{id}")
     */
    public function deleteIssue($id): Response
    {
        $databasePath = __DIR__."/../../var/issues.json";
        $issue = displayIssue($id, $databasePath);

        dump($databasePath);

        if ($issue == null) {
            return new Response($this->renderView("issues/error.html.twig", ["id" => $id + 1]), 404);
        }
        return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue, "id" => $id + 1]));
    }
}
