<?php

namespace App\Service;

use Twig\TwigFunction;
use App\Service\Cart\Cart;
use App\Model\ProductManager;
use App\Model\CategoryManager;
use App\Service\SessionManager;
use Twig\Extension\AbstractExtension;

/**
 * The TwigService class is an extension for Twig that provides custom functions related to session management.
 */
class TwigService extends AbstractExtension
{
    protected $sessionManager;

    /**
     * TwigService constructor.
     *
     * @param SessionManager $sessionManager The session manager instance.
     */
    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * Returns an array of custom Twig functions.
     *
     * @return TwigFunction[] An array of TwigFunction instances.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isLogged', [$this, 'isLogged']),
            new TwigFunction('isAdmin', [$this, 'isAdmin']),
            new TwigFunction('logOut', [$this, 'logOut']),
            new TwigFunction('getCategories', [$this, 'getCategories']),
            new TwigFunction('getTotal', [$this, 'getTotal']),
        ];
    }

    /**
     * Checks if a user is logged in.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function isLogged(): bool
    {
        return $this->sessionManager->isLogged();
    }

    /**
     * Checks if a user is an admin.
     *
     * @return bool True if the user is an admin, false otherwise.
     */
    public function isAdmin(): bool
    {
        return $this->sessionManager->isAdmin();
    }

    /**
     * Logs out the current user.
     */
    public function logOut(): void
    {
        $this->sessionManager->logOut();
    }


    public function getCategories(): array
    {
        $categoryManager = new CategoryManager();
        return $categoryManager->selectAll();
    }

    public function getTotal(): float
    {
        $cart = new Cart($this->sessionManager, new ProductManager());
        return $cart->getTotal();
    }
}
