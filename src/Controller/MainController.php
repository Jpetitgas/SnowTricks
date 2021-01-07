<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(FigureRepository $figureRepository): Response
    {
        $figures = $figureRepository->findAll();
        return $this->render('main/index.html.twig', [
            'figures' => $figures,
        ]);
    }
}
