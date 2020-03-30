<?php

namespace App\Security;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AccountNotActivatedException extends AccountStatusException
{
    /**
    * 
    */
    public function setMessageKey()
    {
        
    }

  
}