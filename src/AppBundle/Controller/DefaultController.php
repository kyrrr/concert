<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $myVar = "Welcome!";
        $myArray = array('🚓' => 'foo', '㊙', '⚰', "🚓");
 
        $foo = $myArray['🚓']; //lol0l

        return $this->render('layout.html.twig', array(
               'myVar' => $myVar,
               'myArray' => $myArray,
               'foo' => $foo,
               ));
    }

    /**
    * @Route("/poke")
    */
    public function pokedex()
    {
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Pokemon');

        $pokemon = $repo->findAll();

        if(!$pokemon){
            throw $this->createNotFoundException(
                'No pokemon found'
            );
        }else{ 

            return $this->render('/pokemon/pokedex.html.twig', array('pokemon' => $pokemon,));
        }
    }

    /**
    * @Route("/poke/id/{id}", name="getPokeById")
    */
    public function getPokeById($id=null)
    {
        if(is_null($id) || !is_numeric($id)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Pokemon');

            $pokemon = $repo->findOneBy(
                array('id' => $id,)
            );

            if (!$pokemon) {
                throw $this->createNotFoundException(
                    'No pokemon found for id '.$id
                );
            }else{

            $name = $pokemon->getName();
            $type = $pokemon->getType();
            $description = $pokemon->getDescription();
            $icon = $pokemon->getIcon();
            
            return $this->render('/pokemon/singlePokemon.html.twig', array(
                   'id' => $id,
                   'name' => $name,
                   'type' => $type,
                   'description' => $description,
                   'icon' => $icon,
                   ));
            }   
        }
    }

    /**
    * @Route("/poke/name/{name}", name="getPokeByName")
    */
    public function getPokeByName($name=null)
    {
        if(is_null($name) || is_numeric($name)){

            return $this->render('failure.html.twig', array());

        }else{
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Pokemon');

            $pokemon = $repo->findOneBy(
                array('name' => $name,)
            );

            if (!$pokemon) {
                return $this->render('failure.html.twig', array());
            }else{

            $id = $pokemon->getId();
            $type = $pokemon->getType();
            $description = $pokemon->getDescription();
            $icon = $pokemon->getIcon();
            
            return $this->render('/pokemon/singlePokemon.html.twig', array(
                   'id' => $id,
                   'name' => $name,
                   'type' => $type,
                   'description' => $description,
                   'icon' => $icon,
                   ));
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
                ->getRepository('AppBundle:Pokemon');

            $pokemon = $repo->findOneBy(
                array('id' => $id,)
            );

            if (!$pokemon) {
                throw $this->createNotFoundException(
                    'No pokemon found for name '.$name
                );
            }else{
                $icon = $pokemon->getIcon();
                $name = $pokemon->getName();

                $form = $this->createForm(PokemonType::class, $pokemon);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $pokemon = $form->getData();
                    $entityManger = $this->getDoctrine()->getManager();
                    $entityManger->persist($pokemon);
                    $entityManger->flush(); 

                    return $this->redirectToRoute('getPokeById', array('id' => $id));
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
