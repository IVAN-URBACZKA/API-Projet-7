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

class UserController extends AbstractController
{
   
     /**
     * @Route("/api/user/new", name="add_user", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'User bien ajoute'
        ];
        return new JsonResponse($data, 201);
    }

     /**
     * @Route("/api/users/{page<\d+>?1}", name="list_users", methods={"GET"})
     */
    public function index(Request $request,UserRepository $repo, SerializerInterface $serializer)
    {
       $page = $request->query->get('page');
       if(is_null($page) || $page < 1) {
        $page = 1;
    }
       $limit = 10;
       $users = $repo->findAllUsers($page, $limit);
       $data = $serializer->serialize($users, 'json');
       return new Response($data, 200, [
           'Content-Type' => 'application/json'
       ]);

    }

         /**
     * @Route("/api/user/{id}", name="show_user", methods={"GET"})
     */
    public function show(User $user, UserRepository $repo, SerializerInterface $serializer)
    {
        $user = $repo->find($user->getId());
        $data = $serializer->serialize($user, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("api/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function delete(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response(null, 204);
    }
}
