<?php

namespace App\Service;

class SessionManager implements SessionInterface
{
    public function __construct(?string $cacheExpire = null, ?string $cacheLimiter = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
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
        return $this->has($key) ? $_SESSION[$key] : null;
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

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function clear(): void
    {
        session_unset();
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function addFlash(string $type, string $message): void
    {
        $flashMessages = $this->get('flash_messages') ?? [];
        $flashMessages[] = ['type' => $type, 'message' => $message];
        $this->set('flash_messages', $flashMessages);
    }

    public function getFlashMessages(): array
    {
        $flashMessages = $this->get('flash_messages') ?? [];
        $this->remove('flash_messages');
        return $flashMessages;
    }
}
