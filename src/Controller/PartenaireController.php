<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Partenaire;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class PartenaireController extends AbstractController
{
    /**
     * @Route("/regpart", name="register_partenaire")
     * @IsGranted("ROLE_SUPER-ADMIN")
     */
   
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
       
            $partenaire = new Partenaire();
            $partenaire->setRaisonSociale($values->raison_sociale);
            $partenaire->setNinea($values->ninea);
            $partenaire->setNumcompte($values->numcompte);
            $partenaire->setSolde($values->solde);
            
            $errors = $validator->validate($partenaire);

            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($partenaire);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'partenaire a été créé'
            ];

            return new JsonResponse($data, 201);
               
    }
    /**
     * @Route("/listerpart", name="listepart", methods={"GET"})
     * @IsGranted("ROLE_SUPER-ADMIN")
    */
    public function index(PartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {
        $partenaire = $partenaireRepository->findAll();
        $data = $serializer->serialize($partenaire, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

       /**
        * @Route("/depot" , name="depot" , methods={"POST"})
        */
        public function depot(Request $request,PartenaireRepository $repository, SerializerInterface $serializer, EntityManagerInterface $entityManager)
        { 
           
            $values = json_decode($request->getContent());

            $repository = $this->getDoctrine()->getRepository(Partenaire::class);
            $part = $repository->findOneBy(['numcompte'=>$values->numcompte]);
            
            $solde = $part->getSolde();

            $part->setSolde($solde + $values->solde);
            $entityManager->persist($part);
            
            if($part){
                $repository = $this->getDoctrine()->getRepository(Partenaire::class);
            $part = $repository->find($part);
                $depot= new Depot();
                $depot->setCreatedAt(new \Datetime);
                $depot->setMontant($values->solde);
                $depot->setPartenaire($part);
                
            }else{
               
                $data = [
                    'status' =>500,
                    'message' => 'Le partenaire n\'existe pas'
                ];
    
                return new JsonResponse($data, 500);
            }
            $entityManager->persist($depot);


            $entityManager->flush();
            $data = [
                'status' => 201,
                'message' => 'L\'partenaire a été créé'
            ];

            return new JsonResponse($data, 201);
            }
            
    
}
