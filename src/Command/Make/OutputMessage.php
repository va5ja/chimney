<?php


namespace Plista\Chimney\Command\Make;

use Plista\Chimney\Console;

/**
 *
 */
class OutputMessage extends Console\OutputMessage
{
    /**
     * @param string $changelogAddon
     * @param string $changelogPath
     */
    public function appendChangelogInfo($changelogAddon, $changelogPath) {
        $this->appendHeader('Generated changelog:');
        $this->append($changelogAddon);
        $this->appendComment("The changelog was added to {$changelogPath}. You don't need to edit it manually.");
    }
}
