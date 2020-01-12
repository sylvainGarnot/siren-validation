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
}
