<?php
/**
 * (c) Sergey Novikov (novikov.stranger@gmail.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Crystals\Config;

use Crystals\Utils\Arr;
use Crystals\Utils\File;

/**
 * Class Config
 * @package Crystals\Config
 */
class Config
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $files
     */
    public function load(array $files)
    {
        foreach ($files as $filename) {
            $this->items = array_merge($this->items, $this->loadFile($filename));
        }
    }

    /**
     * @param string|array $path
     * If $path is string, then parts of the path are separated by slash e.g. "html/defaults/js"
     * @param mixed $default
     * @throws ConfigException
     * @return array|string
     */
    public function get($path, $default = null)
    {
        $item = $this->findItem($path);
        if (is_null($item)) {
            if (is_null($default)) {
                throw new ConfigException("Item `$path` not found in config");
            }
            $item = $default;
        }

        return $item;
    }

    /**
     * Checks whether there is item in config
     *
     * @param string $path Parts of the path are separated by colons e.g. "html:defaults:js"
     * @return bool
     */
    public function exist($path)
    {
        return (bool) $this->findItem($path);
    }

    /**
     * @param string $filename
     * @return array
     */
    private function loadFile($filename)
    {
        return Arr::cast((new File())->setName($filename)->load(true));
    }

    /**
     * @param string|array $path
     * @return array|null
     */
    protected function findItem($path)
    {
        $currentPointer = $this->items;
        foreach ((is_string($path) ? explode('/', $path) : $path) as $part) {
            $currentPointer = isset($currentPointer[$part]) ? $currentPointer[$part] : null;
            if (null === $currentPointer) {
                return $currentPointer;
            }
        }

        return $currentPointer;
    }
}
