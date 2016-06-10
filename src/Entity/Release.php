<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Entity;

class Release implements ReleaseInterface
{
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var DateTimeInterface
     */
    protected $datetime;
    /**
     * @var AuthorInterface
     */
    protected $author;
    /**
     * @var string
     */
    private $packageName = '';

    /**
     * Release constructor.
     * @param VersionInterface $version
     * @param DateTimeInterface $datetime
     * @param AuthorInterface $author
     */
    public function __construct(VersionInterface $version, DateTimeInterface $datetime, AuthorInterface $author)
    {
        $this->version = $version;
        $this->datetime = $datetime;
        $this->author = $author;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatetimeFormatted($format)
    {
        return $this->datetime->format($format);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return $this->author->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorEmail()
    {
        return $this->author->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getVersionFormatted()
    {
        return $this->version->export();
    }

    /**
     * @param string $packageName
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * {@inheritdoc}
     */
    public function getPackageName()
    {
        return $this->packageName;
    }
}