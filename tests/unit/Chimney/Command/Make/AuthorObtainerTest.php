<?php


namespace Plista\Chimney\Test\Unit\Command\Make;

use PHPUnit\Framework\TestCase;
use Plista\Chimney\Command\Make\AuthorLoader;
use Plista\Chimney\Entity\Author;
use Plista\Chimney\System\GitCommandInterface;

/**
 *
 */
class AuthorLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function get() {
        $name = 'John Doe';
        $email = 'john.doe@example.net';

        $command = $this->prophesize(GitCommandInterface::class);
        $command->getUserName()->shouldBeCalled()->willReturn($name);
        $command->getUserEmail()->shouldBeCalled()->willReturn($email);

        $author = (new AuthorLoader())
            ->load(new Author(), $command->reveal());
        $this->assertEquals($name, $author->getName());
        $this->assertEquals($email, $author->getEmail());
    }
}
