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
     * @Route("/api/user", name="add_user", methods={"POST"})
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
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     description="The user you want add",
     *     @SWG\Schema(
     *         @SWG\Property(property="email", type="string", example="exemple@exemple.com"),
     *         @SWG\Property(property="firstname", type="string", example="John"),
     *         @SWG\Property(property="name", type="string", example="Smith"),
     *         @SWG\Property(property="city", type="string", example="Paris"),
     *         @SWG\Property(property="adress", type="string", example="3, rue de martinville")
     *     )
     * )
     * 
     * @SWG\Tag(name="users")
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
        $user->setLinks(array(array(
            'self' => '/api/user/'.$user->getId().'',
            'delete' => '/api/user/'.$user->getId().''
        )));
        $entityManager->flush();

        $objUser = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstname(),
            'adress' => $user->getAdress(),
            'city' => $user->getCity(),
            'email' => $user->getEmail()
        ];
        

        $data = $serializer->serialize($objUser,'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

     /**
     * @Route("/api/users/{page<\d+>?1}", name="list_users", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @SWG\Response(
     *     response=200,
     *     description="The list of all Users",
     *     @Model(type=User::class, groups={"list"})
     * )
     * 
     *      * @SWG\Response(
     *     response=404,
     *     description="Error : The page must be between X and X."
     * )
     * 
     * @SWG\Parameter(
     *     name="users",
     *     in="query",
     *     type="string",
     *     description="List of users"
     * )
     * @SWG\Tag(name="users")
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
     * @Route("/api/user/{id}", name="show_user", methods={"GET"})
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
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Search for a username with a ID"
     * )
     * 
     * @SWG\Tag(name="users")
     * 
     */

    public function show(User $user, UserRepository $repo, SerializerInterface $serializer)
    {
        $user = $repo->findBy(
            ['id' => $user->getId()],
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
     *     description="User successfully deleted"
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Returned when ressource is not yours"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * 
     * @SWG\Tag(name="users")
     */
    public function delete(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response(null, 204);
    }
}
