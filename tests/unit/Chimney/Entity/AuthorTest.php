<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Changelog;

use Plista\Chimney\Entity\Author;

/**
 *
 */
class AuthorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Author
     */
    private $author;

    protected function setUp()
    {
        $this->author = new Author();
    }

    /**
     * @test
     * @dataProvider provideNames
     * @param $inputName
     * @param $outputName
     */
    public function name_setAndGet($inputName, $outputName)
    {
        $this->author->setName($inputName);
        $this->assertEquals($outputName, $this->author->getName());
    }

    /**
     * @test
     * @dataProvider provideEmails
     * @param string $inputEmail
     * @param string $outputEmail
     */
    public function email_setAndGet($inputEmail, $outputEmail)
    {
        $this->author->setEmail($inputEmail);
        $this->assertEquals($outputEmail, $this->author->getEmail());
    }

    /**
     * @test
     */
    public function settersChain()
    {
        $result = $this->author
           ->setName('John Doe')
           ->setEmail('john.doe@example.net')
           ->setName('Charles Barkley');
        $this->assertSame($this->author, $result);
        $this->assertEquals('Charles Barkley', $this->author->getName());
    }

    /**
     * @return array
     */
    public function provideNames()
    {
        return [
           ['Manu Ginobili', 'Manu Ginobili'],
           ['Doctor John A. Zoidberg', 'Doctor John A. Zoidberg'],
           ['Superman', 'Superman']
        ];
    }

    /**
     * @return array
     */
    public function provideEmails()
    {
        return [
           ['ap@plista.com', 'ap@plista.com'],
           ['A@palamarchuk.info', 'a@palamarchuk.info'],
           ['john.Doe@example.net', 'john.doe@example.net']
        ];
    }
}
