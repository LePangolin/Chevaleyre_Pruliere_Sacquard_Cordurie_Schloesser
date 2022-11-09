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
            'title' => 'Auth',
        ]);
    }

    public function profile(Request $request, Response $response, array $args): Response{
        if(!isset($_SESSION['user'])){
            return $response->withHeader('Location', '/auth')->withStatus(302);
        }
        return $this->twig->render($response, 'profile.html.twig', [
            'title' => 'Profile',
        ]);
    }


    public function login(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $user = $this->userService->login($data['username'], $data['password']);

        if ($user === null) {

            $resp = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );

            $response->getBody()->write(json_encode($resp));

            return $response;
        }

        $_SESSION['user'] = $user;

        $resp = array(
            'status' => 'success',
            'message' => 'Logged in successfully'
        );

        $response->getBody()->write(json_encode($resp));

        return $response;
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
}