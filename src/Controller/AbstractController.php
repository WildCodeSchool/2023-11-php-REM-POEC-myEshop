<?php

namespace App\Controller;

use Twig\Environment;
use App\Service\TwigService;
use App\Service\SessionManager;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $session = new SessionManager();

        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new TwigService($session));
    }
}
