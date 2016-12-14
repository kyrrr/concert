<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Poke;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Pokemon;
use AppBundle\Form\PokemonType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select($qb->expr()->count('pokemon.id'));
        $qb->from('AppBundle:Pokemon','pokemon');
        $count = $qb->getQuery()->getSingleScalarResult();

        //$count = $this->rowCount('pokemon.id', 'AppBundle:Pokemon');

        //for ($i = $count + 1; $i < $count + 1 + 500 ; $i++) {
        /*for($i=1;$i<152;$i+=1){
            $pokemon = new Pokemon();
            $pokemon->setName('name'.$i);
            $pokemon->setType('type'.$i);
            $pokemon->setDescription('description'.$i);
            $pokemon->setIcon($i);
            $entityManager->persist($pokemon);
        }
        $entityManager->flush();*/


        //$newCount = $this->rowCount('pokemon.id', 'AppBundle:Pokemon');
        return new Response("count:".$count);
        //return new Response("This is the root. not much to look at");

    }

    /**
     * @Route("/android/addResult/{examId}/{candidate}/{grade}")
     */
    function addResult($examId="", $candidate="", $grade=""){
        return new JsonResponse(
            array(
                'exam_id' => $examId,
                'candidate_id' => $candidate,
                'grade' => $grade,
                'added' => true
                )
            );
    }


    function error($errorThings){
        foreach ($errorThings as $error) {
            echo $error;
        }
    }

    /**
     * @Route("/frame/{foo}")
     */
    public function frame($foo=0){
        $url = "http://localhost:8888/concert/web/app_dev.php/flex/";
        $lol = '
        <!doctype html>
        <html>
        <head></head>
        <body><iframe src="'.$url.$foo.'"></iframe>
        </body>
        <html>
        ';
        return $this->render(
            'flex/frame.html.twig',
            array(
                'lol' => $lol,
            )
        );
    }

    /**
     * @Route("/flex/{foo}")
     */
    public function flex($foo=0){
        //$url="http://www.dagbladet.no";
        //"http://localhost:8888/concert/web/app_dev.php/flex/"
        $lorem = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.';
        $ipsum = 'All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
        return $this->render(
            'flex/pg.html.twig',
            array(
                'lorem' => $lorem,
                'ipsum' => $ipsum,
                'foo' => $foo,
            )
        );
    }

    /**
    * @Route("/poke/")
    */
    public function pokedex()
    {
    
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Pokemon');

        $pokemon = $repo->findAll();

        $generationCutoff = array(
            'Gen1' => 151, 
            'Gen2' => 251,
            );

        if(!$pokemon){
            throw $this->createNotFoundException(
                'No pokemon found'
            );
        }else{ 
            return $this->render('/pokemon/pokedex.html.twig', array('pokemon' => $pokemon, 'generationCutoff' => $generationCutoff));
        }
    }

    /**
    * Mass edits of db content
    * @Route("/poke/edit")
    */
    public function editPokedex()
    {
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Pokemon');

        $pokemon = $repo->findAll();

        if(!$pokemon){
            throw $this->createNotFoundException(
                'No pokemon found'
            );
        }else{ 

            return $this->render('/pokemon/editPokedex.html.twig', array('pokemon' => $pokemon,));
        }
    }

    /**
    * @Route("/poke/id/{id}", name="getPokeById")
    */
    public function getPokeById($id=null)
    {
        /*$em=$this->getDoctrine()->getManager();
        for($i=1;$i<152;$i+=1){
            $pokemon = new Poke();
            $pokemon
                ->setName('name'.$i)
                ->setType('type'.$i)
                ->setDescription('description'.$i)
                ->setIcon($i)
                ->setHeight($i + $i)
                ->setWeight($i + $i)
                ;

            $em->persist($pokemon);
        }
        $em->flush();
        return new Response($i);
        die();*/

        if(is_null($id) || !is_numeric($id)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Poke');
            //NB:poke is for android api, Pokemon for symfony

            $pokemon = $repo->findOneBy(
                array('id' => $id,)
            );

            if (!$pokemon) {
                throw $this->createNotFoundException(
                    'No pokemon found for id '.$id
                );
            }else{

                $id = $pokemon->getId();
                $name = $pokemon->getName();
                $type = $pokemon->getType();
                $description = $pokemon->getDescription();
                $icon = $pokemon->getIcon();
                $weight = $pokemon->getWeight();
                $height = $pokemon->getHeight();
                
                return new JsonResponse(array(
                    'id' => $id,
                    'name' => $name,
                    'type' => $type,
                    'description' => $description,
                    'icon' => $icon,
                    'weight' => $weight,
                    'height' => $height
                ));
            
            /*return $this->render('/pokemon/singlePokemon.html.twig', array(
                   'id' => $id,
                   'name' => $name,
                   'type' => $type,
                   'description' => $description,
                   'icon' => $icon,
                   ));*/
            }   
        }
    }

    /**
    * @Route("/poke/name/{name}", name="getPokeByName")
    */
    public function getPokeByName($name="")
    {
        if(strlen($name) == 0 || is_numeric($name)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Poke');

            $pokemon = $repo->findOneBy(
                array('name' => $name,)
            );

            if (!$pokemon) {
                return new JsonResponse(array('error' => 'Oh nooo'));
                //return $this->render('failure.html.twig', array());
            }else{

            $id = $pokemon->getId();
            $type = $pokemon->getType();
            $description = $pokemon->getDescription();
            $icon = $pokemon->getIcon();
            $weight = $pokemon->getWeight();
            $height = $pokemon->getHeight();

            return new JsonResponse(array(
                'id' => $id,
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'icon' => $icon,
                'weight' => $weight,
                'height' => $height
            ));
            
            /*return $this->render('/pokemon/singlePokemon.html.twig', array(
                   'id' => $id,
                   'name' => $name,
                   'type' => $type,
                   'description' => $description,
                   'icon' => $icon,
                   ));*/
            }   
        }
    }

    /**
    * @Route("/poke/edit/{var}")
    */
    public function editPoke($var=null)
    {
        if(!is_null($var)){
            if(is_numeric($var)){
                return $this->redirectToRoute('editPokeById', array('id' => $var));
            }elseif(is_string($var)){ 
                return $this->redirectToRoute('editPokeByName', array('name' => $var));
            }
        }else{
            return $this->render('failure.html.twig', array());
        }
    }

    /**
    * @Route("/poke/edit/id/{id}", name="editPokeById")
    */
    public function editPokeById($id='', Request $request)
    {
        if(is_null($id) || !is_numeric($id)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Poke');

            $pokemon = $repo->findOneBy(
                array('id' => $id,)
            );

            if (!$pokemon) {
                throw $this->createNotFoundException(
                    'No pokemon found for id '.$id
                );
            }else{
                $icon = $pokemon->getIcon();
                $name = $pokemon->getName();
                $weight = $pokemon->getWeight();
                $height = $pokemon->getHeight();

                $form = $this->createForm(PokemonType::class, $pokemon);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $pokemon = $form->getData();
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($pokemon);
                    $entityManager->flush();
                    return $this->redirectToRoute('editPokeById', array('id' => $id - 1));
                }

                return $this->render('/pokemon/editPokemon.html.twig',
                    array(
                      'form' => $form->createView(),
                      'id' => $id,
                      'icon' => $icon,
                      'name' => $name,
                      'height' => $height,
                      'weight' => $weight,
                       ));
            }
        }
    }

    /**
     * @Route("/poke/getIcon/id/{id}", name="getIconById")
     */
    public function getIconById($id=0){
        if($id > 0 && $id < 152){
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Poke');

            $pokemon = $repo->findOneBy(
                array(
                    'id' => $id,
                    )
            );

            if($pokemon){
                return new JsonResponse(array(
                    'id' => $id,
                    'android_animated_icon_path' => 'http://10.0.2.2:8888/concert/web/assets/custom/images/pokemon/xy-animated-shortened/'.$id.'.gif',
                    'android_gen1_icon_path' => 'http://10.0.2.2:8888/concert/web/assets/custom/images/pokemon/gen1/'.$id.'.png',
                    )
                );
            }else{
                return new JsonResponse(array(
                    'error' => 'No poke found for id '.$id,
                    )
                );
            }

        }else{
            return new JsonResponse(array(
                'error' => 'selection (id) out of bounds'
                )
            );
        }
    }

    /**
    * @Route("/poke/edit/name/{name}", name="editPokeByName")
    */
    public function editPokeByName($name='', Request $request)
    {
        if(is_null($name) || !is_string($name)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Pokemon');

            $pokemon = $repo->findOneBy(
                array('name' => $name,)
            );

            if (!$pokemon) {
                throw $this->createNotFoundException(
                    'No pokemon found for name '.$name
                );
            }else{

                $id = $pokemon->getId();
                $icon = $pokemon->getIcon();
                $name = $pokemon->getName();

                $form = $this->createForm(PokemonType::class, $pokemon);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $pokemon = $form->getData();
                    $entityManger = $this->getDoctrine()->getManager();
                    $entityManger->persist($pokemon);
                    $entityManger->flush();

                    $editedName = $pokemon->getName();    

                    return $this->redirectToRoute('getPokeByName', array('name' => $editedName));
                }

                return $this->render('/pokemon/editPokemon.html.twig', array(
                      'form' => $form->createView(),
                      'id' => $id,
                      'icon' => $icon,
                      'name' => $name,
                       ));
            }

            
        }
    }

}
