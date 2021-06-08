<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
protected $router;
protected $encoder;


public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder)
{
    
    $this->router = $router;
    $this->encoder = $encoder;
}

public function getCredentials(Request $request)
{
    if($request->getPathInfo() != '/userDetails')
    {
        return;
    }
    $email = $request->request->get('email');
       // dd($email);

    $password = $request->request->get('password');
    $request->getSession()->set(
        CoreSecurity::LAST_USERNAME, $email);

    return [
        'email' => $email,
        'password' => $password,
    ];
}

public function getUser($credentials, UserProviderInterface $userProvider)
{
    $email = $credentials['email'];
    return $userProvider->loadUserByUsername($email);
}

public function checkCredentials($credentials, UserInterface $user)
{
    $plainPassword = $credentials['password'];
    if($this->encoder->isPasswordValid($user, $plainPassword))
    {
        return true;
    }
    throw new BadCredentialsException();
}

public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
{
    $url = $this->router->generate('userDetails');
    return new RedirectResponse($url);
}

public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
{
    $request->getSession()->set(CoreSecurity::AUTHENTICATION_ERROR, $exception);
    $url = $this->router->generate('app_login');
    return new RedirectResponse($url);
}

protected function getLoginUrl()
{
    return $this->router->generate('app_login');
}

protected function getDefaultSuccessRedirectUrl()
{
    return $this->router->generate('welcome');
}

public function supports(Request $request)
{
}

public function supportsRememberMe()
{
}


}