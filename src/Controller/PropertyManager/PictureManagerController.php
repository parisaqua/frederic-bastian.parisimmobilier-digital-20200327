<?php

namespace App\Controller\PropertyManager;



use App\Entity\Picture;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





/**

 * @Route("/gestion/photo")

 */

class PictureManagerController extends AbstractController {



    /**

     * @Route("/{id}", name="picture.manager.delete", methods={"DELETE"})

     */

    public function delete(Request $request, Picture $picture): Response

    {

        // $propertyId = $picture->getProperty()->getId();



        $data = json_decode($request->getContent(), true);



        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $data['_token'])) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($picture);

            $entityManager->flush();

            return new JsonResponse(['success' => 1]);

        }



        return new JsonResponse(['error' => 'Token invalide'], 400);

    }



}