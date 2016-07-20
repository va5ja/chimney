<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Export;

use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Changelog\GeneratorInterface;

/**
 *
 */
abstract class ChangelogUpdater implements ChangelogUpdaterInterface
{
    /**
     * @var Template\LoaderInterface
     */
    protected $templateLoader;

    /**
     * DebianChangelogUpdater constructor.
     * @param Template\LoaderInterface $templateLoader
     */
    public function __construct(Template\LoaderInterface $templateLoader)
    {
        $this->templateLoader = $templateLoader;
    }

    /**
     * @return string
     */
    abstract protected function getTemplate();

    /**
     * {@inheritdoc}
     */
    public function append(ChangelogFileInterface $file, GeneratorInterface $generator)
    {
        $changelogAddon = $generator->makeChangelog($this->getTemplate());
        $file->add($changelogAddon);
        return $changelogAddon;
    }
}
