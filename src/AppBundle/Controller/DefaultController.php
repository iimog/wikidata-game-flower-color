<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Color;
use AppBundle\Entity\Plant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use AppBundle\API\API;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/insertColors", name="insert_colors")
     */
    public function insertColorsAction(Request $request)
    {
        $colors = array(
            'blue'    => 'Q1088',
            'brown'   => 'Q47071',
            'cyan'    => 'Q180778',
            'green'   => 'Q3133',
            'magenta' => 'Q3276756',
            'orange'  => 'Q39338',
            'purple'  => 'Q3257809',
            'red'     => 'Q3142',
            'white'   => 'Q23444',
            'yellow'  => 'Q943'

        );
        foreach($colors as $c => $wdid){
            $color = new Color();
            $color->setColor($c);
            $color->setWikidataId($wdid);
            $em = $this->getDoctrine()->getManager();
            $em->persist($color);
        }
        $em->flush();
        $color_count = count($this->getDoctrine()->getRepository('AppBundle:Color')->findAll());
        return new Response('Saved colors in the database. Total colors now: '.$color_count);
    }

    /**
     * @Route("/insertPlants", name="insert_plants")
     */
    public function insertPlantsAction(Request $request)
    {
        if (($handle = fopen(__DIR__."/../../../data/plants.tsv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $plant = new Plant();
                $plant->setWikidataId($data[0]);
                $plant->setScientificName($data[1]);
                $plant->setFinished(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($plant);
            }
            fclose($handle);
            $em->flush();
        }
        $plant_count = count($this->getDoctrine()->getRepository('AppBundle:Plant')->findAll());
        return new Response('Saved plants in the database. Total plants now: '.$plant_count);
    }

    /**
     * @param $request Request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api", name="api")
     */
    public function apiAction(Request $request){
        $queryData = new ParameterBag(array_merge($request->query->all(), $request->request->all()));
        if(!$queryData->has('callback')){
            throw new MissingMandatoryParametersException('Mandatory parameter "callback" is missing');
        }
        if(!$queryData->has('action')){
            throw new MissingMandatoryParametersException('Mandatory parameter "action" is missing');
        }
        $service = new API($queryData);
        $result = "";
        switch ($queryData->get('action')){
            case 'desc':
                $result = $service->getDesc();
                break;
            case 'tiles':
                $result = $service->getTiles();
                break;
            case 'log_action':
                $result = $service->getLogAction();
                break;
            default:
                $result = array('error' => 'No valid action!');
        }
        $response = new JsonResponse($result);
        $response->setCallback($queryData->get('callback'));
        return $response;
    }
}
