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


        [$result, $message] = MQTTService::sendToRaspberry(['color' => DataService::hex2rgb($color)]);

        return $this->redirect('app_index');
//        return $this->json([
//            'status'  => $result,
//            'message' => $message,
//        ]);
    }

    /**
     * @Route("/on_off", name="app_on_off")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function onOff(
        Request $request
    ) : Response {
        $state = (bool) $request->get('on_off', 0);

        [$result, $message] = MQTTService::sendToRaspberry(['state' => $state]);

        return $this->redirect('app_index');
//        return $this->json([
//            'status'  => $result,
//            'message' => $message,
//        ]);
    }
}