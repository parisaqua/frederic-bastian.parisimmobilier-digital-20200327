<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\PasswordUpdate;
use App\Form\AdminAccountType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAccountController extends AbstractController
{  
    /**
     * Permet d'afficher la liste des users
     * @Route("/admin/users", name="admin.user.index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')", message="Vous n'avez pas les droits pour accèder à cette page.")
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findActive(),
            'menu' => 'adminUser'
        ]);
    }

        /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account.show")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount() {
        return $this->render('account/show.html.twig', [
            'user' => $this->getUser()
        ]);

    }

    /**
     * Permet d'ajouter un user
     * @Route("/admin/user/new", name="admin.user.new")
     * @return response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(AdminAccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé. Vous pouvez maintenant vous connecter."
            );

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de modifier un user
     * @Route("admin/user/{id}/edition", name="admin.user.edit", methods={"GET","POST"})
     * @return Response
     */
    public function profile(Request $request, User $user, EntityManagerInterface $manager): Response {
        
        $form = $this->createForm(AdminAccountType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données ont été enregistrées avec succès !"
            );

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }



}
