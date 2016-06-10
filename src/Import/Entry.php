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

use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\Change;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\DateTime;

/**
 * @todo Make access the $lifeFields array abstract
 */
class Entry
{
    const FIELD_INDEX_DATETIME = 0;
    const FIELD_INDEX_AUTHOR_NAME = 1;
    const FIELD_INDEX_AUTHOR_EMAIL = 2;
    const FIELD_INDEX_SUBJECT = 3;
    /**
     * @var string[]
     */
    private $lineFields;

    /**
     * Entry constructor.
     * @param array $lineFields
     */
    public function __construct(array $lineFields)
    {
        $this->lineFields = $lineFields;
    }

    /**
     * @return DateTime
     */
    public function getDatetime()
    {
        return new DateTime($this->lineFields[self::FIELD_INDEX_DATETIME]);
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        $author = new Author();
        $author->setName($this->lineFields[self::FIELD_INDEX_AUTHOR_NAME]);
        $author->setEmail($this->lineFields[self::FIELD_INDEX_AUTHOR_EMAIL]);
        return $author;
    }

    /**
     * @return Change
     */
    public function getSubject()
    {
        $parser = new SubjectParser($this->lineFields[self::FIELD_INDEX_SUBJECT]);
        return new Change(
           $parser->getSubject(),
           ChangeType::newFromArray($parser->getTags())
        );
    }
}
