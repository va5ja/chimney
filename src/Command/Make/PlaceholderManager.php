<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command\Make;

/**
 *
 */
class PlaceholderManager
{
    const VERSION = '%VERSION%';
    const PACKAGE_NAME = '%PACKAGE%';
    const CHANGELOG_FILE = '%CHANGELOGFILE%';

    private static $known = [
        self::VERSION,
        self::PACKAGE_NAME,
        self::CHANGELOG_FILE,
    ];

    /**
     * @var array Array of [placeholder=>value_on_replace] pairs.
     */
    private $placeholders = [];

    /**
     * @param string $placeholder Placeholder.
     * @param string $value Value to replace the placeholder.
     * @return $this
     * @throws ExitException
     */
    public function collect($placeholder, $value) {
        if (!$this->isKnown($placeholder)) {
            throw new ExitException(
                "Placeholder {$placeholder} is not recognized",
                ExitException::STATUS_SCRIPT_CANNOT_RUN
            );
        }
        $this->placeholders[$placeholder] = $value;
        return $this;
    }

    /**
     * @param string $subject
     * @return string
     */
    public function inject($subject) {
        return str_replace(array_keys($this->placeholders), $this->placeholders, $subject);
    }

    /**
     * Checks a placeholder is known to the class
     * @param string $placeholder
     * @return bool
     */
    public function isKnown($placeholder) {
        return in_array($placeholder, self::$known);
    }

    /**
     * Extracts all the known placeholders from a given subject.
     * @param string $subject
     * @return string[]
     */
    public function extract($subject) {
        if (!preg_match_all('/(%[A-Z]+%)/', $subject, $matches)) {
            return [];
        }
        return array_values(array_filter($matches[1], function($placeholder) {
            return $this->isKnown($placeholder);
        }));
    }
}
