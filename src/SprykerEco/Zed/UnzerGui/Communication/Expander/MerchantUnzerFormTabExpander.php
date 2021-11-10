<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Expander;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class MerchantUnzerFormTabExpander implements MerchantUnzerFormTabExpanderInterface
{
    /**
     * @var string
     */
    protected const UNZER_TAB_ITEM_TEMPLATE = '@UnzerGui/_partials/unzer-tab.twig';

    /**
     * @var string
     */
    protected const UNZER_TAB_ITEM_NAME = 'unzer';

    /**
     * @var string
     */
    protected const UNZER_TAB_ITEM_TITLE = 'Unzer';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())->setName(static::UNZER_TAB_ITEM_NAME)
            ->setTitle(static::UNZER_TAB_ITEM_TITLE)
            ->setTemplate(static::UNZER_TAB_ITEM_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
