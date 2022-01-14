<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class UnzerCredentialsFormTabs extends AbstractTabs
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addGeneralTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralTab(TabsViewTransfer $tabsViewTransfer): UnzerCredentialsFormTabs
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName('general')
            ->setTitle('General')
            ->setTemplate('@UnzerGui/_partials/general-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer): UnzerCredentialsFormTabs
    {
        $tabsViewTransfer->setFooterTemplate('@UnzerGui/_partials/_form-submit.twig')
            ->setIsNavigable(true);

        return $this;
    }
}
