<?php

namespace App\Controller;

use App\Entity\Phone;
use Swagger\Annotations as SWG;
use Swagger\Annotations\Schema;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class PhoneController extends AbstractController
{
    /**
     * 
     * 
     * @Route("/api/phones/{page<\d+>?1}", name="list_phone", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * 
     *  @SWG\Response(
     *     response=201,
     *     description="Returns list of phones details",
     *   @Model(type=Phone::class, groups={"list"}),
     *  @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phone::class))
     *     )
     *     
     * )
     * 
     * @SWG\Response(
     *     response=404,
     *     description="Error : The page must be between X and X."
     * )
     * 
     *  @SWG\Parameter(
     *     name="phones",
     *     in="query",
     *     type="string",
     *     description="Search list of phones"
     * )
     * @SWG\Tag(name="phones")
     * 
     */
    public function index(Request $request,PhoneRepository $repo, SerializerInterface $serializer)
    {
       $page = $request->query->get('page');
       if($page === null || $page < 1) {
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
     * @IsGranted("ROLE_USER")
     * 
     *   
     * @SWG\Response(
     *     response=200,
     *     description="Returns phonr details",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="id number of the product"
     * )
     * @SWG\Tag(name="phones")
     * 
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
