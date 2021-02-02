<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Image\MainImage;
use App\Media\MediaManager;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureController extends AbstractController
{
    protected $mainImage;
    protected $MediaManager;

    public function __construct(MainImage $mainImage, MediaManager $mediaManager)
    {
        $this->MediaManager = $mediaManager;
        $this->mainImage = $mainImage;
    }

    /**
     * @Route("/figure/{slug}", name="figure_show", priority=-1)
     */
    public function show($slug, Request $request, Figure $figure, CommentController $commentController, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $page = $request->query->get('page', 1);
        $comments = $commentController->showComments($request, $figure, $page);
        if (!($comments)) {
            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
        }
        if ($request->get('ajax')) {
            return new JsonResponse(['contenu' => $this->renderView('figure/_commentaires.html.twig', compact('comments')), 'page' => ++$page]);
        }

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'comments' => $comments[0],
            'formView' => $comments[1],
        ]);
    }

    /**
     * @Route("/figure/create", name="figure_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, Security $securit, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setWriter($securit->getUser());
            $this->MediaManager->manager($form->get('images')->getData(), $form->get('media')->getData(), $figure, 'create');
            $em->persist($figure);
            $em->flush();
            $this->mainImage->mainImageNewFigure($figure);
            $this->addFlash('success', 'La figure a été créée');

            return $this->RedirectToRoute('main');
        }
        $fromView = $form->CreateView();

        return $this->render('figure/create.html.twig', [
            'formView' => $fromView,
        ]);
    }

    /**
     * @Route("/figure/edit/{slug}", name="figure_edit", priority=-1)
     * @IsGranted("ROLE_USER")
     */
    public function edit($slug, Figure $figure, Request $request, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->MediaManager->manager($form->get('images')->getData(), $form->get('media')->getData(), $figure, 'edit');
            $figure->setWriter($securit->getUser());
            $em->flush();
            $this->mainImage->changeMainImage($figure->getId(), $form->get('main')->getData());
            $this->addFlash('success', 'La figure a été modifée');

            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
        }
        $fromView = $form->createView();

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
            'formView' => $fromView,
        ]);
    }

    /**
     * @Route("/figure/delete/{slug}/{token}", name="figure_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete($slug, FigureRepository $figureRepository, Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy([
            'slug' => $slug,
        ]);
        $ref = htmlspecialchars('delete'.$figure->getId().$figure->getSlug());
        $token = new CsrfToken($ref, $request->attributes->get('token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token n\'est pas valide.');
        }
        if (!$figure) {
            throw $this->createNotFoundException("Cette figure n'existe pas");
        }
        $em->remove($figure);
        $em->flush();

        return $this->RedirectToRoute('main');
    }
}
