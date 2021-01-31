<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/delete/media/{id}", name="figure_delete_media", methods={"DELETE"})
     */
    public function deletemedia(Media $media, Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $ref = htmlspecialchars('delete'.$media->getId());
        if ($this->isCsrfTokenValid($ref, $data['_token'])) {
            //suppression du fichier
            $em = $this->getDoctrine()->getManager();
            $em->remove($media);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalid'], 400);
        }

        return new JsonResponse(['error' => 'Une figure doit posséder au moins une media!'], 400);
    }
}
