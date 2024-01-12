<?php

namespace App\Service;

/**
 * Class SessionManager
 *
 * This class is responsible for managing sessions in the application.
 * It provides methods for starting, accessing, and destroying sessions.
 *
 * @package App\Service
 */
class SessionManager implements SessionInterface
{
    public function __construct(?int $cacheExpire = null, ?string $cacheLimiter = null)
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            if ($cacheLimiter !== null) {
                session_cache_limiter($cacheLimiter);
            }

            if ($cacheExpire !== null) {
                session_cache_expire($cacheExpire);
            }

            session_start();
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->has($key) ? $_SESSION[$key] : [];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return SessionManager
     */
    public function set(string $key, $value): SessionManager
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Removes a session variable by key.
     *
     * @param string $key The key of the session variable to remove.
     * @return void
     */
    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clears all session variables.
     *
     * @return void
     */
    public function clear(): void
    {
        session_unset();
    }

    /**
     * Destroys the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->clear();
        session_destroy();
    }

    /**
     * Checks if a session variable exists.
     *
     * @param string $key The key of the session variable to check.
     * @return bool True if the session variable exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Adds a flash message to the session.
     *
     * @param string $type The type of the flash message.
     * @param string $message The content of the flash message.
     * @return void
     */
    public function addFlash(string $type, string $message): void
    {
        $flashMessages = $this->get('flash_messages') ?? [];
        $flashMessages[] = ['type' => $type, 'message' => $message];
        $this->set('flash_messages', $flashMessages);
    }

    /**
     * Retrieves and removes all flash messages from the session.
     *
     * @return array|null The array of flash messages.
     */
    public function getFlashMessages(): array|null
    {
        $flashMessages = $this->get('flash_messages') ?? [];
        $this->remove('flash_messages');
        return $flashMessages;
    }


    /**
     * Checks if a user is logged in.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }


    /**
     * Checks if the logged-in user is an admin.
     *
     * @return bool True if the logged-in user is an admin, false otherwise.
     */
    public function isAdmin(): bool
    {
        if (isset($_SESSION['user']['roles'])) {
            return strpos($_SESSION['user']['roles'], 'ROLE_ADMIN') !== false;
        }
        return false;
    }

    /**
     * Logs out the user by removing the 'user' session variable and destroying the session.
     *
     * @return void
     */
    public function logOut(): void
    {
        $this->remove('user');
        $this->destroy();
    }
}
