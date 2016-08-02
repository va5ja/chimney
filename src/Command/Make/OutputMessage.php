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

    /**
     * @param PlaceholderManager $placeholderManager
     */
    public function appendHintDebian(PlaceholderManager $placeholderManager) {
        $commit = '"' . $placeholderManager::PACKAGE_NAME . ' ('. $placeholderManager::VERSION . ')" -- ' . $placeholderManager::CHANGELOG_FILE;
        $this->appendHeader('Release commands:');
        $this->append(
            $placeholderManager->inject(<<<EOT
git checkout next
git pull
git commit -m {$commit}
git push
git checkout master
git pull
git merge next
git push
git checkout next
EOT
        ));
        $this->appendComment('Copy and paste these command into your console for quicker releasing.');
    }

    /**
     * @param PlaceholderManager $placeholderManager
     */
    public function appendHintMd(PlaceholderManager $placeholderManager) {
        $changelogPath = $placeholderManager::CHANGELOG_FILE;
        $version = $placeholderManager::VERSION;

        $this->appendHeader('Release commands:');
        $this->append(
            $placeholderManager->inject(<<<EOT
git commit -m "Update changelog #ign" {$changelogPath}
git tag {$version}
git push
git push --tags
EOT
            ));
        $this->appendComment('Copy and paste these command into your console for quicker releasing.');
    }

}
