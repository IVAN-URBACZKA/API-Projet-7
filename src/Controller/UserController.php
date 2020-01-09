<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;




class UserController extends AbstractController
{
   
     /**
     * @Route("/api/user/new", name="add_user", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * 
     * @SWG\Response(
     *     response=201,
     *     description="create user",
     *     @Model(type=User::class, groups={"list"})
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The JSON sent contains invalid data",
     * )
     * 
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserInterface $customer = null)
    {
      
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        if($user->getCustomerId() == null)
        {
            $user->setCustomerId($customer);
        }
        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->persist($user);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'User bien ajoute'
        ];
        return new JsonResponse($data, 201);
    }

     /**
     * @Route("/api/users/{page<\d+>?1}", name="list_users", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @SWG\Response(
     *     response=200,
     *     description="The list of all Users",
     *     @Model(type=User::class, groups={"list"})
     * )
     */
    public function index(Request $request,UserRepository $repo, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
         $page = 1;
     }
        $limit = 10;
        $users = $repo->findAllUsers($page, $limit);
        $data = $serializer->serialize($users, 'json',[
            'groups' => ['list']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

     /**
     * @Route("/api/user/{name}", name="show_user", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Detail of a particular user",
     *     @Model(type=User::class, groups={"list"})
     * )
     * 
     * @SWG\Response(
     *     response=404,
     *     description="This user does not exists."
     * )
     * 
     */

    public function show(User $user, UserRepository $repo, SerializerInterface $serializer)
    {
        $user = $repo->findBy(
            ['name' => $user->getName()],
            ['firstname' => 'ASC'],
          
        );
        $data = $serializer->serialize($user, 'json',[
            'groups' => ['list']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("api/user/{id}", name="delete_user", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     * 
     * @SWG\Response(
     *     response=204,
     *     description="Delete user",
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="This user does not exists."
     * )
     */
    public function delete(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response(null, 204);
    }
}
