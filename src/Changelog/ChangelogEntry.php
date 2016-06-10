<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Changelog;

use Plista\Chimney\Entity\AuthorInterface;
use Plista\Chimney\Entity\ChangeInterface;
use Plista\Chimney\Entity\DateTimeInterface;

/**
 * Changelog's single entry container.
 *
 * The setters of the class implement method chaining.
 */
class ChangelogEntry implements FormattableInterface, TimeComparableInterface
{
    /**
     * @var ChangeInterface
     */
    protected $change;
    /**
     * @var DateTimeInterface
     */
    protected $datetime;
    /**
     * @var AuthorInterface
     */
    protected $author;

    /**
     * ChangelogEntry constructor.
     * @param ChangeInterface $change
     */
    public function __construct(ChangeInterface $change)
    {
        $this->change = $change;
    }

    /**
     * @param AuthorInterface $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param DateTimeInterface $datetime
     * @return $this
     */
    public function setDatetime(DateTimeInterface $datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Gets formatted datetime of the entry.
     * @see http://php.net/manual/en/function.date.php
     * @param string $format DateTime format string.
     * @return string
     */
    public function getDatetimeFormatted($format)
    {
        return $this->datetime->format($format);
    }

    /**
     * {@inheritdoc}
     */
    public function isEarlierThan(TimeComparableInterface $toCompare)
    {
        return ($this->getDatetime() < $toCompare->getDatetime());
    }

    /**
     * {@inheritdoc}
     */
    public function translatePlaceholder($placeholder)
    {
        switch ($placeholder) {
            case 'ENTRY_SUBJECT':
                return $this->change->getSubject();
            case 'AUTHOR_NAME':
                return $this->author->getName();
            case 'AUTHOR_EMAIL':
                return $this->author->getEmail();
            default:
                throw new Exception("Placeholder \"{$placeholder}\" is unknown to ChangelogEntry");
        }
    }

    /**
     * @return bool
     */
    public function isBreaking()
    {
        return $this->change->isBreaking();
    }

    /**
     * @return bool
     */
    public function isIgnore()
    {
        return $this->change->isIgnore();
    }
}
