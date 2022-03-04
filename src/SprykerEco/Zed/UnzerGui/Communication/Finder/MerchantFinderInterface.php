<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Finder;

interface MerchantFinderInterface
{
    /**
     * @param string|null $merchantReference
     *
     * @return array<string, string>
     */
    public function getMerchants(?string $merchantReference = null): array;
}
