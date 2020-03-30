<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\AccountDisabledException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    
    private  $entityManager;
    public function __construct( EntityManagerInterface $entityManager ) {
        $this->entityManager = $entityManager;
    }
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
  
        // membre inactif
        if (!$user->getIsActive(true)) {

          
            throw new AccountNotActivatedException("Votre compte n'est pas activé.");  
        
        
            // throw new \Exception("Ce compte est inactif");
        
    
         }
    }
   
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
   
       
    }
    
    
    
    // public function checkPreAuth(UserInterface $user)
    // {
    //     if (!$user instanceof AppUser) { 
    //         return;
    //     }
        
    //     // L’utilisateur n’est pas activé par l’administrateur
    //     if (!$user->getIsActive(true)) { 
    //         throw new AccountDisabledException();     
    //     }
       
    // }

    // public function checkPostAuth(UserInterface $user)
    // {
        
    // }
}