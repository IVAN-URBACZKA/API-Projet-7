<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class PhoneController extends AbstractController
{
    /**
     * @Route("/api/phones/{page<\d+>?1}", name="list_phone", methods={"GET"})
     */
    public function index(Request $request,PhoneRepository $repo, SerializerInterface $serializer)
    {
       $page = $request->query->get('page');
       if(is_null($page) || $page < 1) {
        $page = 1;
    }
       $limit = 10;
       $phones = $repo->findAllPhones($page, $limit);
       $data = $serializer->serialize($phones, 'json',[
           'groups' => ['list']
       ]);
       return new Response($data, 200, [
           'Content-Type' => 'application/json'
       ]);

    }

     /**
     * @Route("/api/phone/{id}", name="show_phone", methods={"GET"})
     */
    public function show(Phone $phone, PhoneRepository $repo, SerializerInterface $serializer)
    {
        $phone = $repo->find($phone->getId());
        $data = $serializer->serialize($phone, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
