<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(FigureRepository $figureRepository, Request $request): Response
    {
        $limit = 15;
        $page = (int)$request->query->get("page", 1);

        //$figures = $figureRepository->findAll();
        $figures = $figureRepository->getPaginationFigures($page, $limit);

        $total = $figureRepository->getTotalFigure();

        return $this->render('main/index.html.twig', compact('figures', 'total', 'limit', 'page'));
    }
}
