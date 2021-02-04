<?php

namespace App\Controller;

use App\Entity\Image;
use App\Image\MainImage;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/delete/image/{id}", name="figure_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request, FigureRepository $figureRepository, EntityManagerInterface $em, MainImage $mainImage)
    {
        if ($this->isCsrfTokenValid(htmlspecialchars('delete'.$image->getId()), json_decode($request->getContent(), true)['_token'])) {
            $figure = $figureRepository->findOneBy(['id' => $image->getFigure()]);
            $allImage = $figure->getImages();
            if (count($allImage) >= 2) {
                $name = $mainImage->firstImageMain($image, $allImage);
                if ($this->getParameter('images_directory').'/'.$name) {
                    unlink($this->getParameter('images_directory').'/'.$name);
                }
                $this->getDoctrine()->getManager()->remove($image);
                $this->getDoctrine()->getManager()->flush();

                return new JsonResponse(['success' => 1]);
            }
        } else {
            return new JsonResponse(['error' => 'Token Invalid'], 400);
        }

        return new JsonResponse(['error' => 'Une figure doit posséder au moins une image!'], 400);
    }
}
