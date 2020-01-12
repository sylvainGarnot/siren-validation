<?php

namespace App\Controller;

use App\Entity\Siren;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

class SirenController extends Controller
{
    /**
     * @Get(
     *     path = "/sirens",
     *     name = "app_siren_list"
     * )
     * @View
     */
    public function listAction()
    {
        $sirens = $this->getDoctrine()->getRepository('App:Siren')->findAll();
        $data = $this->get('jms_serializer')->serialize($sirens, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Rest\Post(
     *    path = "/sirens",
     *    name = "app_siren_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("siren", converter="fos_rest.request_body")
     */
    public function createAction(Siren $siren)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($siren);
        $em->flush();
        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Get(
     *     path = "/sirens/{id}",
     *     name = "app_siren_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Siren $siren)
    {
        $data = $this->get('jms_serializer')->serialize($siren, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Delete(
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
        foreach ($siren->getReservations() as $reservation) {
            $em->remove($reservation);
        }
        $em->remove($siren);
        $em->flush();
        return new Response('', Response::HTTP_ACCEPTED);
    }
}
