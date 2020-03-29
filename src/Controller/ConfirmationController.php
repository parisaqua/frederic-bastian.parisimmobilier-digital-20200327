<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Mailer;
use App\Form\ResettingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/renouvellement-mot-de-passe")
 */
class ConfirmationController extends AbstractController
{
    // /**
    //  * @Route("/account/confirm/{token}/{username}", name="confirm_account")
    //  * @param $token
    //  * @param $username
    //  * @return Response
    //  */
    // public function confirmAccount($token, $username): Response
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
    //     $tokenExist = $user->getConfirmationToken();
    //     if($token === $tokenExist) {
    //        $user->setConfirmationToken(null);
    //        $user->setEnabled(true);
    //        $em->persist($user);
    //        $em->flush();
    //        return $this->redirectToRoute('app_login');
    //     } else {
    //         return $this->render('registration/token-expire.html.twig');
    //     }
    // }
    // /**
    //  * @Route("/send-token-confirmation", name="send_confirmation_token")
    //  * @param Request $request
    //  * @param MailerService $mailerService
    //  * @param \Swift_Mailer $mailer
    //  * @return \Symfony\Component\HttpFoundation\RedirectResponse
    //  * @throws \Exception
    //  */
    // public function sendConfirmationToken(Request $request, MailerService $mailerService, \Swift_Mailer $mailer): RedirectResponse
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $email = $request->request->get('email');
    //     $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
    //     if($user === null) {
    //         $this->addFlash('not-user-exist', 'utilisateur non trouvé');
    //         return $this->redirectToRoute('app_register');
    //     }
    //     $user->setConfirmationToken($this->generateToken());
    //     $em->persist($user);
    //     $em->flush();
    //     $token = $user->getConfirmationToken();
    //     $email = $user->getEmail();
    //     $username = $user->getUsername();
    //     $mailerService->sendToken($mailer, $token, $email, $username, 'registration.html.twig');
    //     return $this->redirectToRoute('app_login');
    // }

}