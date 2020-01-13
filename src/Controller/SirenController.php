<?php

namespace App\Controller;

use App\Entity\Siren;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Validator;

class SirenController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/sirens/{siren}",
     *     name = "app_siren_list",
     *     requirements = {"siren"="\d+"}
     * )
     * @View
     */
    public function listAction(Request $request)
    {
        $response = new Response();
        $sirenNumber = $request->get('siren');
        $sirens = $this->getDoctrine()->getRepository('App:Siren')->findBy(['number'=>$sirenNumber]);
        
        if (!$sirenNumber) {
            $response->setContent('vous devez ajouter un numéro de siren')->setStatusCode(404);
        } else if (count($sirens) == 0) {
            $response->setContent('siren non valide')->setStatusCode(404);
        } else {
            $data = $this->get('jms_serializer')->serialize($sirens, 'json');
            $response->setContent($data);
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
     * @Delete(
     *     path = "/sirens/{id}",
     *     name = "app_siren_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function removeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $siren = $em->getRepository('App:Siren')
                    ->find($request->get('id'));
        if (!$siren) {
            return;
        }
        $em->remove($siren);
        $em->flush();
        return new Response('', Response::HTTP_ACCEPTED);
    }
}
