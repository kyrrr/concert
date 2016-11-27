<?php

namespace AppBundle\Controller;

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

    protected function rowCount($tableIndex=false, $package=false)
    {
        $error = [];

        if($tableIndex || $package){
            $error[count($error)+1] = "Missing argument(s) for rowCount() \n ";
        }

        $arrayTableAndIndex = explode('.', $tableIndex);
        $arraySplitPackage = explode(':', $package);

        if(count($arrayTableAndIndex) < 1){
            $error[count($error)+1] = " could not extract table from tableIndex for rowCount()";
        }

        if(count($arrayTableAndIndex) > 2){
            $error[count($error)+1] = " Too many '.' in tableIndex for rowCount()";
        }



            $table = $arrayTableAndIndex[0];

            $entityManager = $this->getDoctrine()->getManager();
            $qb = $entityManager->createQueryBuilder();
            $qb->select($qb->expr()->count($tableIndex));
            $qb->from($package, $table);

            
            return $count = $qb->getQuery()->getSingleScalarResult();

    }

    function error($errorThings){
        foreach ($errorThings as $error) {
            echo $error;
        }
    }

    /**
    * @Route("/poke")
    */
    public function pokedex()
    {
    
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Pokemon');

        $pokemon = $repo->findAll();

        //counts all rows. they are many
        $stressTest = $this->rowCount('pokemon.id', 'AppBundle:Pokemon');

        $generationCutoff = array(
            'Gen1' => 151, 
            'Gen2' => 251,
            'stressTest' => $stressTest,
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
                    'No pokemon found for id '.$id
                );
            }else{
                $icon = $pokemon->getIcon();
                $name = $pokemon->getName();

                $form = $this->createForm(PokemonType::class, $pokemon);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $pokemon = $form->getData();
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($pokemon);
                    $entityManager->flush(); 

                    return $this->redirectToRoute('editPokeById', array('id' => $id - 1));
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
     * @Route("/poke/getIcon/id/{id}", name="getIconById")
     */
    public function getIconById($id=0){
        if($id > 0 && $id < 152){
            $repo = $this->getDoctrine()
                ->getRepository('AppBundle:Pokemon');

            $pokemon = $repo->findOneBy(
                array(
                    'id' => $id,
                    )
            );

            if($pokemon){
                return new JsonResponse(array(
                    'id' => $id,
                    'icon_path' => 'http://10.0.2.2:8888/concert/web/assets/custom/images/pokemon/'.$id.'.png',
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
