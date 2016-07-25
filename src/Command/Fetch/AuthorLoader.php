<?php


namespace Plista\Chimney\Command\Fetch;

use Plista\Chimney\Entity\Author;
use Plista\Chimney\System\GitCommandInterface;

/**
 * @todo It's definitely a Gypsy wagon anti-pattern so far. Must be re-worked after decomposing MakeCommand.
 */
class AuthorLoader
{
    /**
     * Loads Author with required information obtained from Git and returns the argument.
     * @param Author $author
     * @param GitCommandInterface $command
     * @return Author Author loaded with required information.
     */
    public function load(Author $author, GitCommandInterface $command) {
        $author->setName($command->getUserName());
        $author->setEmail($command->getUserEmail());
        return $author;
    }
}
