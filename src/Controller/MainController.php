<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(FigureRepository $figureRepository, Request $request): Response
    {
        $limit = 10;
        $page = (int) $request->query->get('page', 1);
        $figures = $figureRepository->getPaginationFigures($page, $limit);
        ++$page;
        if ($request->get('ajax')) {
            return new JsonResponse([
            'contenu' => $this->renderView('main/_content.html.twig', compact('figures')),
            'page' => $page,
            ]);
        }

        return $this->render('main/index.html.twig', compact('figures'));
    }
}
