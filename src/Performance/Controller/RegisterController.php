<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Performance\Domain\UseCase\SignUp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use League\Flysystem\FilesystemInterface;

class RegisterController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var UrlGeneratorInterface
     */
    private $url_generator;

    /**
     * @var SignUp
     */
    private $useCase;

    /**
     * @var FilesystemInterface
     */
    private $fs;

    public function __construct(\Twig_Environment $templating, UrlGeneratorInterface $url_generator, SignUp $useCase, FilesystemInterface $fs) {
        $this->template = $templating;
        $this->url_generator = $url_generator;
        $this->useCase = $useCase;
        $this->fs = $fs;
    }

    public function get()
    {
        return new Response(
            $this->template->render('register.twig'),
            200,
            [ 'Cache-Control' => 's-maxage=300, private' ]
        );
    }

    public function post(Request $request)
    {
    	$username = $request->request->get('username');
    	$password = $request->request->get('password');
        $image    = $request->files->get('image');

        $filename = $username.'.png';

        if ($this->fs->has($filename)) {
            $this->fs->delete($filename);
        }

        $this->fs->writeStream(
            $filename,
            fopen($image->getRealPath(), 'r')
        );

    	$this->useCase->execute($username, $password);

        return new RedirectResponse($this->url_generator->generate('login'));
    }
}
