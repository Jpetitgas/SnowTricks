<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\FigureRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{

    /**
     * @Route("/delete/image/{id}", name="figure_delete_image", methods={"DELETE"}) 
     */
    public function deleteImage(Image $image, Request $request, FigureRepository $figureRepository, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $ref= htmlspecialchars('delete' . $image->getId());
        if ($this->isCsrfTokenValid($ref, $data['_token'])) {
            $id_figure = $image->getFigure();
            $figure = $figureRepository->findOneBy(['id' => $id_figure]);
            $allImage = $figure->getImages();
            if (count($allImage) >= 2) {
                if ($image->getMain()) {
                    if ($image==$allImage[0]){
                    $newMainImage = $allImage[1];
                    $newMainImage->setmain(TRUE);
                    } else {
                        $newMainImage = $allImage[0];
                        $newMainImage->setmain(TRUE);
                    }
                }
                //suppression du fichier
                $name = $image->getName();
                if ($this->getParameter('images_directory') . '/' . $name) {
                    unlink($this->getParameter('images_directory') . '/' . $name);
                }
                $em = $this->getDoctrine()->getManager();
                $em->remove($image);
                $em->flush();

                return  new JsonResponse(['success' => 1]);
            }
        } else {
            return  new JsonResponse(['error' => 'Token Invalid'], 400);
        }
        return  new JsonResponse(['error' => 'Une figure doit posséder au moins une image!'], 400);
    }
}
