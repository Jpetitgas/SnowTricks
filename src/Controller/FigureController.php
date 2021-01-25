<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Media\AddMedia;
use App\Form\FigureType;
use App\Image\MainImage;
use App\Form\CommentType;
use App\Image\UpLoadImages;
use App\Repository\FigureRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    protected $mainImage;
    protected $upLoadImages;
    protected $addMedia;


    public function __construct(MainImage $mainImage, upLoadImages $upLoadImages, AddMedia $addMedia)
    {
        $this->addMedia = $addMedia;
        $this->mainImage = $mainImage;
        $this->upLoadImages = $upLoadImages;
    }


    /**
     * @Route("/figure/{slug}", name="figure_show", priority=-1)
     */
    public function show($slug, Request $request, CommentRepository $commentRepository, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );

        $limit = 5;
        $page = (int)$request->query->get("page", 1);
        $comments = $commentRepository->getPaginationComments($figure, $page, $limit);

        if (!$figure) {
            $this->addFlash('danger', "Cette figure n'existe pas");
            return $this->RedirectToRoute('main');
        }

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDate(new DateTime())
                ->setWriter($user)
                ->setFigure($figure);
            $em->persist($comment);
            $em->flush();
            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
        }
        $page++;
        if ($request->get('ajax')) {
            return new JsonResponse([
                'contenu' => $this->renderView('figure/_commentaires.html.twig', compact('comments')),
                'page' => $page
            ]);
        }

        $fromView = $form->createView();

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'comments' => $comments,
            'formView' => $fromView
        ]);
    }
    /**
     * @Route("/figure/create", name="figure_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, Security $securit, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $figure = new Figure;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            if (!$images) {
                $this->upLoadImages->upLoadDefault($figure);
            } else {
                $this->upLoadImages->upLoad($images, $figure);
            }

            $media = $form->get('media')->getData();
            if ($media) {
                $this->addMedia->addUrl($media, $figure);
            }

            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            $figure->setDate(new DateTime());
            $figure->setDateMod(new DateTime());
            $em->persist($figure);
            $em->flush();

            //Mise à la une de la premier image
            $this->mainImage->mainImageNewFigure($figure);

            $this->addFlash('success', "La figure a été créée");

            return $this->RedirectToRoute('main');
        }

        $fromView = $form->CreateView();

        return $this->render('figure/create.html.twig', [
            'formView' => $fromView
        ]);
    }

    /**
     * @Route("/figure/edit/{slug}", name="figure_edit", priority=-1)
     * @IsGranted("ROLE_USER")
     */
    public function edit($slug, Request $request, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $figure = $figureRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );

        if (!$figure) {
            $this->addFlash('danger', "Cette figure n'existe pas");
            return $this->RedirectToRoute('main');
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            if ($images) {
                $this->upLoadImages->upLoad($images, $figure);
            }

            $media = $form->get('media')->getData();
            if ($media) {
                $this->addMedia->addUrl($media, $figure);
            }


            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            
            $figure->setDateMod(new DateTime());

            $em->flush();
            //reglage de l'image principale 
            $newMainImage = $form->get('main')->getData();
            $figure_id = $figure->getId();
            $this->mainImage->changeMainImage($figure_id, $newMainImage);

            $this->addFlash('success', "La figure a été modifée");

            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
        }

        $fromView = $form->createView();

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
            'formView' => $fromView
        ]);
    }

    /**
     * @Route("/figure/delete/{slug}", name="figure_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete($slug, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$figure) {
            throw $this->createNotFoundException("Cette figure n'existe pas");
        }

        $em->remove($figure);
        $em->flush();
        $this->addFlash('success', "La figure a été supprimée");
        return $this->RedirectToRoute('main');
    }
}
