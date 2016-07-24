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

use Plista\Chimney\Export;
use Plista\Chimney\Changelog\Template;

/**
 *
 */
class ChangelogUpdaterFactory
{
    const TYPE_DEBIAN = 'debian';
    const TYPE_MD = 'md';

    /**
     * @param string $changelogType
     * @param Template\LoaderInterface $templateLoader
     * @return Export\ChangelogUpdaterInterface
     * @throws ExitException
     */
    public function create($changelogType, Template\LoaderInterface $templateLoader) {
        switch ($changelogType) {
            case self::TYPE_DEBIAN:
                return new Export\DebianChangelogUpdater($templateLoader);
                /*
                if (!$packageName) {
                    $this->throwException(
                        "The \"package\" option must set when generating a debian changelog",
                        MakeCommand::EXIT_STATUS_ILLEGAL_COMMAND
                    );
                }
                */
            case self::TYPE_MD:
                return new Export\MdChangelogUpdater($templateLoader);
            default:
                $this->throwException(
                    "The changelog type is not recognized",
                    ExitException::STATUS_CHANGELOG_TYPE_UNKNOWN
                );
        }

    }

    /**
     * @param string $error
     * @param int $code
     * @throws ExitException
     */
    private function throwException($error, $code)
    {
        throw new ExitException($error, $code);
    }
}
