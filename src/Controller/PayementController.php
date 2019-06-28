<?php

namespace App\Controller;

use App\Entity\Payement;
use App\Form\PayementFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Abraham\TwitterOAuth\TwitterOAuth;

class PayementController extends AbstractController
{
    /**
     * @Route("/payement", name="payement")
     */
    public function index(Request $request)
    {
        //Récupérer les infos de l'API Villo
        $id_villo = $request->get('id');
        $address_villo = $request->get('address');
        $name_villo = $request->get('name');

        $vars = ['id_villo' => $id_villo,
            'address_villo' => $address_villo,
            'name_villo' => $name_villo];


        //Créer un nouveau payement
        $payement = new Payement();

        $form = $this->createForm(PayementFormType::class, $payement);
        $form->handleRequest($request);

        //Si le formulaire est envoyé
        if($form->isSubmitted()){
            $data = $form->getData();
            $stripeToken = $request->request->get('stripeToken');

            if($request->request->get('stripeToken')) {

                $payment = $this->createPayment($stripeToken, $data->getLastnameCustomer(), $data->getFirstnameCustomer(), $data->getEmailCustomer());

                if($payment) {
                    $payement->setIdVillo(intval($id_villo))
                        ->setAddressVillo($address_villo)
                        ->setNameVillo($name_villo);


                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($payement);
                    $entityManager->flush();
                } else {
                    $this->addFlash('success', 'Il y a une erreur avec la paiement.');

                    return $this->render('payement/index.html.twig', [
                        'vars' => $vars,
                        'payementForm' => $form->createView(),
                    ]);
                }

            } else {
                $this->addFlash('success', 'Il y a une erreur avec la paiement.');

                return $this->render('payement/index.html.twig', [
                    'vars' => $vars,
                    'payementForm' => $form->createView(),
                ]);
            }


            return $this->redirectToRoute('home_index');
        }

        return $this->render('payement/index.html.twig', [
            'vars' => $vars,
            'payementForm' => $form->createView(),
        ]);
    }

    private function createPayment($stripeToken, $lastname, $firstname, $email){

        return true;

        //Importer la librairie Stripe
        require_once('../../vendor/stripe/autoload.php');

        //Instancier Stripe
        \Stripe\Stripe::setApiKey("sk_test_8qUiE7pBzDwX3PwSswVm2k9H00U0xFphV6");

        //Création d'un utilisateur Stripe
        $customer = \Stripe\Customer::create([
            "email" => $email,
            "source" => $stripeToken,
            "metadata" => [
                "firstname" => $firstname,
                "lastname" => $lastname,
            ]
        ]);

        //Création d'un charge Stripe
        $charge = \Stripe\Charge::create([
            "customer" => $customer->id,
            "amount" => (1 * 100), //= 1€
            "currency" => "eur",
            // "source" => $stripeToken, // obtained with Stripe.js
            "description" => "Test Stripe", //L'intitulé qui sera affiché sur la ligne de débit de compte en banque du client
            "receipt_email" => "sarahcosyns1@gmail.com" //pour envoyer une confirmation de paiement
        ]);


        //Vérification de la charge
        if ($charge->paid) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @Route("/payement/card", name="payement_card")
     */
    public function payement_card()
    {

        if (isset($_POST['stripeToken']) && !empty($_POST['stripeToken'])) {

            //Importer la librairie Stripe
            require_once('vendor/autoload.php');

            //Instancier Stripe
            \Stripe\Stripe::setApiKey("sk_test_8qUiE7pBzDwX3PwSswVm2k9H00U0xFphV6");

            //Stoker dans une varaible le token récupérer depuis le formulaire
            $stripeToken = $_POST['stripeToken'];

            //Création d'un utilisateur Stripe
            $customer = \Stripe\Customer::create([
                "email" => "sarahcosyns1@gmail.com",
                "source" => $stripeToken,
                "metadata" => [
                    "firstname" => "Sarah",
                    "lastname" => "Cosyns",
                    "address" => "rue de la Station 37C, 1440 Braine-le-chateau",
                    "phone" => "0487425805"
                ]
            ]);

            //Création d'un charge Stripe
            $charge = \Stripe\Charge::create([
                "customer" => $customer->id,
                "amount" => (1 * 100), //= 1€
                "currency" => "eur",
                // "source" => $stripeToken, // obtained with Stripe.js
                "description" => "Test Stripe", //L'intitulé qui sera affiché sur la ligne de débit de compte en banque du client
                "receipt_email" => "sarahcosyns1@gmail.com" //pour envoyer une confirmation de paiement
            ]);


            //Vérification de la charge
            if ($charge->paid) {
                die('Good');
            }

        } else {
            echo 'Passer par le fomulaire';
            die();
        }


        return $this->render('payement/payement_card.html.twig', []);
    }

    /**
     * @Route("/api/twitter", name="api_twitter")
     */
    public function apiTwitter()
    {
        require_once ('../vendor/abraham/twitteroauth/autoload.php');
        require_once ('../vendor/Embera/Autoload.php');


        // Paramètres de l'API Twitter : https://apps.twitter.com/
        $twitter_customer_key           = 'G3qmvD0dMcOtElxdgeo0uwVjW';
        $twitter_customer_secret        = 'CUBxpq8bHyiD6FbzXEjzO5FxD4gjfID3w5LpXx2AbVPduE2c2g';
        $twitter_access_token           = NULL ;
        $twitter_access_token_secret    = NULL ;

        // Connection à l'API
        $connection = new TwitterOAuth($twitter_customer_key, $twitter_customer_secret, $twitter_access_token, $twitter_access_token_secret);

        // Chargement du système twitter-text-php pour créer des liens Html dans les Tweets
        $embera = new \Embera\Embera();

        // Paramètres de récupération du/des Tweets : voir les paramètres sur https://dev.twitter.com/rest/reference/get/statuses/user_timeline
        $tweets = $connection->get('statuses/user_timeline', array('screen_name' => 'villo_brussels', 'count' => 10));

        foreach ($tweets as $k => $tweet) {

            // Création de la date
            $timestamp = strtotime($tweet->created_at);
            $date = new \DateTime();
            $date->setTimestamp($timestamp);
            $lastTweets[$k]['Tweet']['date'] = $date;

            if(isset($tweet->errors))
            {
                $lastTweets[$k]['Tweet']['text'] = 'Error :'. $tweet->errors[0]->code. ' - '. $tweets->errors[0]->message;
            }else{
                if(substr($tweet->text, 0, 2) != 'RT' ){
                    $lastTweets[$k]['Tweet']['text'] = utf8_decode($embera->autoEmbed($tweet->text));
                } else {
                    $lastTweets[$k]['Tweet']['text'] = utf8_decode($embera->autoEmbed($tweet->retweeted_status->text));
                }
            }
        }
    }
}


