<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UnzerGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const UNZER_MERCHANT_SUGGESTION_LIMIT = 20;

    /**
     * @var array<int, string>
     */
    protected const UNZER_CREDENTIALS_TYPE = [
        1 => 'Standard',
        2 => 'Marketplace (Main channel)',
    ];

    /**
     * @api
     *
     * @return array<string>
     */
    public function getUnzerCredentialTypeChoices(): array
    {
        return static::UNZER_CREDENTIALS_TYPE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantSuggestionLimit(): int
    {
        return static::UNZER_MERCHANT_SUGGESTION_LIMIT;
    }
}
