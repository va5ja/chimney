<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Repository;

use Plista\Chimney\Entity\Version;
use Plista\Chimney\System\GitCommandInterface;

class GitPackageInfoObtainer implements PackageInfoInterface
{
    /**
     * @var GitCommandInterface
     */
    private $command;

    /**
     * GitPackageInfoObtainer constructor.
     * @param GitCommandInterface $command
     */
    public function __construct(GitCommandInterface $command)
    {
        $this->command = $command;
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * Returns the latest tagged version.
     * {@inheritdoc}
     */
    public function getVersion()
    {
        $tag = $this->command->getLastTag();
        $semanticVerParsed = explode(Version::SEPARATOR, $tag);
        if (count($semanticVerParsed) != 3) {
            throw new \Exception("The tag {$tag} obtained from the repository is not a valid semantic version");
        }
        $version = new Version($semanticVerParsed[0], $semanticVerParsed[1], $semanticVerParsed[2]);
        return $version;
    }
}