<?php


namespace App\Controller;


use App\Service\DataService;
use App\Service\MQTTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(
        Request $request
    ) : Response {
        return $this->render('index.html.twig', []);
    }

    /**
     * @Route("/set_rgb", name="app_set_rgb")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function setRGB(
        Request $request
    ) : Response {
        $color = $request->get('favcolor', '#1837e7');


        [$result, $message] = MQTTService::sendToRaspberry(DataService::hex2rgb($color));
        return $this->json([
            'status'  => $result,
            'message' => $message,
        ]);
    }
}