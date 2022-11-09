<?php 

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;
use Slim\Views\Twig;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService, Twig $twig)
    {
        $this->userService = $userService;
        $this->twig = $twig;
    }


    public function auth(Request $request, Response $response, array $args): Response
    {
        
        return $this->twig->render($response, 'authentification.html.twig', [
            'title' => 'Authentification',
        ]);
    }

    public function displayProfile(Request $request, Response $response, array $args): Response{
        if(!isset($_SESSION['user'])){
            return $response->withHeader('Location', '/auth')->withStatus(302);
        }
        $tabInfo = $this->userService->getUserInfo($_SESSION['user']->getId());
        return $this->twig->render($response, 'profile.html.twig', [
            'title' => 'Profile',
            'userGalleries' => $tabInfo['MyGalleries'],
        ]);
    }


    public function login(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $user = $this->userService->login($data['username'], $data['password']);

        if ($user === null) {

            return $this->twig->render($response, 'authentification.html.twig', [
                'title' => 'Auth',
                'error' => 'Wrong username or password',
            ]);

        }

        $_SESSION['user'] = $user;

        return $response->withHeader('Location', '/profile')->withStatus(302);
    }

    public function signUp(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $bool = $this->userService->signUp($data['username'], $data['password']);

        // TODO: Redirection
        if ($bool === false) {
            $resp = array(
                'status' => 'error',
                'message' => 'User already exists'
            );
        }else{
            $resp = array(
                'status' => 'success',
                'message' => 'User created'
            );
        }

        $response->getBody()->write(json_encode($resp));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        unset($_SESSION['user']);
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}