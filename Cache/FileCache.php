<?php
namespace Cache\Cache;

class FileCache extends CacheProvider
{
    /**
     * The cache directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * The cache file extension.
     *
     * @var string
     */
    protected $extension;

    /**
     * @var int
     */
    private $umask;

    /**
     * @var int
     */
    private $directoryStringLength;

    /**
     * @var int
     */
    private $extensionStringLength;

    /**
     * @var bool
     */
    private $isRunningOnWindows;

    /**
     * FileCache constructor.
     * @param string $directory The cache directory.
     * @param string $extension The cache file extension.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($directory, $extension = '', $umask = 0002)
    {
        if (! is_int($umask)) {
            throw new \InvalidArgumentException(sprintf(
                'The umask parameter is required to be  integer, was: %s',
                gettype($umask)
            ));
        }
        $this->umask = $umask;

        if (! $this->createPathIfNeeded($directory)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" does not exist and could not be created.',
                $directory
            ));
        }

        if (! is_writeable($directory)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writable.',
                $directory
            ));
        }

        $this->directory = realpath($directory);
        $this->extension = (string) $extension;

        $this->directoryStringLength = strlen($this->directory);
        $this->extensionStringLength = strlen($this->extension);
        $this->isRunningOnWindows = defined('PHP_WINDOWS_VERSION_BUILD');
    }

    /**
     * Gets the cache directory.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Gets the cache file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param $id
     * 
     * @return string
     */
    protected function getFilename($id)
    {
        $hash = hash('sha256', $id);

        // This ensures that the filename is unique and that there are no invalid chars in it.
        if (
            '' === $id
            || ((strlen($id) * 2 + $this->extensionStringLength) > 255)
            || ($this->isRunningOnWindows && ($this->directoryStringLength + 4 + strlen($id) * 2 + $this->extensionStringLength) > 258)
        ) {
            $filename = '_' . $hash;
        } else {
            $filename = bin2hex($id);
        }

        return $this->directory
            . DIRECTORY_SEPARATOR
            . substr($hash, 0, 2)
            . DIRECTORY_SEPARATOR
            . $filename
            . $this->extension;
    }

    /**
     * Create path if needed.
     *
     * @param $path
     * @return bool TRUE on success or if path already exists. FALSE if path cannot be created.
     */
    private function createPathIfNeeded($path)
    {
        if (! is_dir($path)) {
            if (false === @mkdir($path, 0777 & (~$this->umask), true) && !is_dir($path)) {
                return false;
            }
        }

        return true;
    }
}