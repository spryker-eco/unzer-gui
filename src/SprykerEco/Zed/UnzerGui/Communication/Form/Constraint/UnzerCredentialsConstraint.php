<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form\Constraint;

use SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UnzerCredentialsConstraint extends SymfonyConstraint
{
    /**
     * @var \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface
     */
    protected $unzerFacade;

    /**
     * @param \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface $unzerFacade
     * @param array $options
     */
    public function __construct(
        UnzerGuiToUnzerFacadeInterface $unzerFacade,
        array $options = []
    ) {
        $this->unzerFacade = $unzerFacade;

        parent::__construct($options);
    }

    /**
     * @return \SprykerEco\Zed\UnzerGui\Dependency\UnzerGuiToUnzerFacadeInterface
     */
    public function getUnzerFacade(): UnzerGuiToUnzerFacadeInterface
    {
        return $this->unzerFacade;
    }
}
