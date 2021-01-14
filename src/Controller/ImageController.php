<?php

namespace App\Controller;

use App\Entity\Image;
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
    public function deleteImage(Image $image, Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $name = $image->getName();
            if ($this->getParameter('images_directory') . '/' . $name) {
                unlink($this->getParameter('images_directory') . '/' . $name);
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            return  new JsonResponse(['success' => 1]);
        } else {
            return  new JsonResponse(['error' => 'Token Invalid'], 400);
        }
    }
}
