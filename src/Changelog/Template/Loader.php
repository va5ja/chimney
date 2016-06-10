<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Changelog\Template;

/**
 *
 */
class Loader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadDebian()
    {
        return $this->getTemplateContents('debian');
    }

    /**
     * {@inheritdoc}
     */
    public function loadMd()
    {
        return $this->getTemplateContents('md');
    }

    /**
     * @param string $tplFilename
     * @return string
     */
    public function getTemplateContents($tplFilename)
    {
        return file_get_contents(CHIMNEY_CHANGELOG_TEMPLATE_DIR . "/{$tplFilename}.chimney");
    }
}