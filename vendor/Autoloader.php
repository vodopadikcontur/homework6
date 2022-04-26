<?php

declare(strict_types=1);

class Autoloader
{
    private array $prefixes = [];

    public function register(): void
    {
        spl_autoload_register([$this, 'autoload']);
    }

    public function addNamespace(string $prefix, string $dir): void
    {
        if (!file_exists($dir)) {
            throw new \RuntimeException("$dir does not exist");
        }

        $prefix = trim($prefix, '\\') . '\\';

        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . '/';

        if (!isset($this->prefixes[$prefix][$dir])) {
            $this->prefixes[$prefix][] = $dir;
        }
    }

    public function autoload(string $class): void
    {
        $namespace = $class;

        while (false !== $slashesPosition = strrpos($namespace, '\\')) {

            $namespace = substr($class, 0, $slashesPosition + 1);

            $this->files(
                $namespace,
                substr($class, $slashesPosition + 1)
            );

            $namespace = rtrim($namespace, '\\');
        }
    }

    protected function files(string $prefix, string $class): bool
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $base_dir) {

            $file = sprintf('%s%s%s', $base_dir, str_replace('\\', '/', $class), '.php');

            if (file_exists($file)) {
                require_once $file;
            }
        }

        return true;
    }
}
