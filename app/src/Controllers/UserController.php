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

    // public function login(Request $request, Response $response, array $args): Response
    // {
    //     $data = $request->getParsedBody();

    //     $user = $this->userService->login($data['username'], $data['password']);

    //     if ($user === null) {
    //         $response->getBody()->write('Invalid credentials');
    //         return $response->withStatus(401);
    //     }

    //     $_SESSION['user'] = $user;

    //     return $this->twig->render($response, 'index.html.twig', [

    //     ]);
    // }

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