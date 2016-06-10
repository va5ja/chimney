<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Export;

/**
 *
 */
class ChangelogFile implements ChangelogFileInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * DebianChangelogUpdater constructor.
     * @param string $path
     * @throws Exception
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new Exception("The changelog file {$path} is not found");
        }
        $this->path = $path;
    }

    /**
     * @param string $addon
     */
    public function add($addon)
    {
        $this->prepend($addon);
    }

    /**
     * @param $addon
     */
    private function prepend($addon)
    {
        $handle = fopen($this->path, "r+");
        if (false === $handle) {
            throw new Exception("The changelog file {$this->path} cannot be open for updating");
        }

        $len = strlen($addon);
        $finalLen = filesize($this->path) + $len;
        $old = fread($handle, $len);
        rewind($handle);
        $i = 1;
        while (ftell($handle) < $finalLen) {
            fwrite($handle, $addon);
            $addon = $old;
            $old = fread($handle, $len);
            fseek($handle, $i * $len);
            $i++;
        }
    }
}
