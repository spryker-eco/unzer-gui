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
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\ListUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_LIST = '/unzer-gui/list-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-unzer-credentials';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\EditUnzerCredentialsController::activateAction()
     *
     * @var string
     */
    public const URL_UNZER_CREDENTIALS_SYNC = '/unzer-gui/edit-unzer-credentials/sync';

    /**
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\RemoveUnzerCredentialsController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_REMOVE = '/unzer-gui/remove-unzer-credentials';

    /**
     * @var int
     */
    protected const COMPANY_SUGGESTION_LIMIT = 20;

    /**
     * @var array
     */
    protected const UNZER_CREDENTIALS_TYPES = [
        1 => 'Standard',
        2 => 'Marketplace (Main channel)',
    ];

    /**
     * @api
     *
     * @return array<string>
     */
    public function getUnzerCredentialsTypeChoices(): array
    {
        return static::UNZER_CREDENTIALS_TYPES;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantSuggestionLimit(): int
    {
        return static::COMPANY_SUGGESTION_LIMIT;
    }
}
