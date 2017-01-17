<?php
/**
 * Autoloader
 *
 * Load namespaces and classes following the WordPress naming convertions.
 * https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions
 */
namespace ClaudioSanches\WPAutoloader;

/**
 * Autoloader.
 */
class Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Register loader with SPL autoloader stack.
     *
     * @codeCoverageIgnore
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix  The namespace prefix.
     * @param string $baseDir A base directory for class files in the
     * namespace.
     */
    public function addNamespace(string $prefix, string $baseDir)
    {
        $prefix  = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (false === isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        array_push($this->prefixes[$prefix], $baseDir);
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param  string $class The fully-qualified class name.
     * @return string        The mapped file name on success, or emtpy string on
     * failure.
     */
    public function loadClass($class): string
    {
        $prefix = $class;

        while (false !== ($position = strrpos($prefix, '\\'))) {
            $prefix = substr($class, 0, $position + 1);
            $relativeClass = substr($class, $position + 1);

            if ($mappedFile = $this->loadMappedFile($prefix, $relativeClass)) {
                return $mappedFile;
            }

            // Remove the trailing namespace separator for the next iteration.
            $prefix = rtrim($prefix, '\\');
        }

        return '';
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix        The namespace prefix.
     * @param string $relativeClass The relative class name.
     *
     * @return string               Empty string if no mapped file can be
     * loaded, or the name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        if (false === isset($this->prefixes[$prefix])) {
            return '';
        }

        $relativeFile = $this->getRelativeFile($relativeClass);

        // Look through base directories for this namespace prefix.
        foreach ($this->prefixes[$prefix] as $baseDir) {
            $file = $baseDir . $relativeFile;

            // If the mapped file exists, require it.
            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return '';
    }

    /**
     * Normalize path using WordPress naming convertions.
     *
     * @param  string $path Relative class path.
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        return str_replace('_', '-', strtolower($path));
    }

    /**
     * Get relative file path.
     *
     * @param  string $relativeClass Relative class.
     * @return string
     */
    protected function getRelativeFile(string $relativeClass): string
    {
        // Class file names should be based on the class name with
        // "class-" prepended and the underscores in the class name
        // replaced with hyphens.
        $relative = $this->normalizePath($relativeClass);
        $pieces   = explode('\\', $relative);
        $last     = array_pop($pieces);
        $last     = 'class-' . $last . '.php';
        $pieces[] = $last;

        return implode(DIRECTORY_SEPARATOR, $pieces);
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @codeCoverageIgnore
     *
     * @param  string $file The file to require.
     * @return bool         True if the file exists, false if not.
     */
    protected function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
