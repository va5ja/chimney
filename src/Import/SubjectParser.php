<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Import;

/**
 *
 */
class SubjectParser
{
    /**
     * @var string
     */
    private $subjInput;

    /**
     * SubjectParser constructor.
     * @param string $subjInput
     */
    public function __construct($subjInput)
    {
        $this->subjInput = $subjInput;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return preg_replace("~{$this->getTagWildcard()}~", '', $this->subjInput);
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        preg_match_all("~{$this->getTagWildcard()}~", $this->subjInput, $matches);
        return $matches[1];
    }

    /**
     * @return string
     */
    private function getTagWildcard()
    {
        return '\s+\#([a-z]+)';
    }
}
