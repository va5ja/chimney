<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Fixture;

/**
 *
 */
trait FixtureProviderTrait
{
    /**
     * @return mixed
     */
    private function getFixturesPath()
    {
        return INTEGRATION_FIXTURE_DIR;
    }

    /**
     * @param string $filePath
     * @return string
     * @throws \Exception
     */
    private function getFixtureFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("The fixture file {$filePath} does not exists");
        }
        return file_get_contents($filePath);
    }

    /**
     * @param string $relativePath
     * @return string
     * @throws \Exception
     */
    protected function getFixture($relativePath)
    {
        return $this->getFixtureFile("{$this->getFixturesPath()}/{$relativePath}");
    }

    /**
     * @param string $relativePath Relative path to the original fixture, without the postfix.
     * @return string
     * @throws \Exception
     */
    protected function getFixtureExpected($relativePath)
    {
        return $this->getFixtureFile("{$this->getFixturesPath()}/{$relativePath}" . '_expected');
    }
}
