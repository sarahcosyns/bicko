<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        /*
        //Adresse de l'URL sur laquelle on va taper pour récuperer le contenu JSON
        $api_url = "https://opendata.bruxelles.be/api/records/1.0/search/?dataset=stations-villo-disponibilites-en-
            temps-reel&rows=100&facet=banking&facet=bonus&facet=status&facet=contract_name";

        // Le contenu brut (au format JSON) issu de l'API
        $api_content = file_get_contents($api_url);

        // Transformation du JSON en Tableau PHP
        $api_content_array = json_decode($api_content);

        // On affiche ce que l'on a réussi à obtenir
        dd($api_content_array);
        */

        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {

        if(
            $request->request->get('lastname') && !empty($request->request->get('lastname'))
            && $request->request->get('firstname') && !empty($request->request->get('firstname'))
            && $request->request->get('g-recaptcha-response') && !empty($request->request->get('g-recaptcha-response')))
        {
            $secret_key = '6LeDG6sUAAAAAD_yjyMqUB8ZQZ3Ip3JO4YQ6wjBR';
            $token = $request->request->get('g-recaptcha-response');

            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$token;
            $response = json_decode(file_get_contents($url));

            if($response->success){

                // Code Custom...

            } else {
                die('Une erreur est survenue avec le Captcha');
            }
        } else {
            die('Veuillez compléter correctement le formulaire');
        }


    }
}
