<?php
namespace Plista\Chimney\Test\Unit\Command;

use Plista\Chimney\Command\MakeCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 *
 */
class MakeCommandTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCommand()
    {
        return new MakeCommand();
    }

    /**
     * @test
     */
    public function execute_wrongType()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->executeCommand(['type' => 'some_wrong_type']);
    }
}
