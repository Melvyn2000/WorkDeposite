<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?RedirectResponse
    {
        //return new Response('Accès refusé !', 403);
        $request->getSession()->getFlashBag()->add('warning', 'Accès refusé !');
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}
