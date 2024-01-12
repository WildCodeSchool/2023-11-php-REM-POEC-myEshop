<?php

namespace App\Service;

interface SessionInterface
{
    /**
     * Retrieves the value associated with the given key from the session.
     *
     * @param string $key The key to retrieve the value for.
     * @return mixed The value associated with the given key.
     */
    public function get(string $key);

    /**
     * Sets the value for the given key in the session.
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to be set.
     * @return SessionInterface The updated SessionInterface instance.
     */
    public function set(string $key, $value): self;

    /**
     * Removes the value associated with the given key from the session.
     *
     * @param string $key The key to remove the value for.
     * @return void
     */
    public function remove(string $key): void;

    /**
     * Clears all values from the session.
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Checks if the session has a value associated with the given key.
     *
     * @param string $key The key to check.
     * @return bool True if the session has a value for the given key, false otherwise.
     */
    public function has(string $key): bool;
}
