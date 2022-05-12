<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class UnzerCredentialsEditForm extends UnzerCredentialsCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, int> $choices
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(UnzerCredentialsTransfer::TYPE, ChoiceType::class, [
            'choices' => array_flip($choices),
            'attr' => ['readonly' => true],
            'label' => static::LABEL_TYPE,
            'placeholder' => 'Select one',
        ]);

        return $this;
    }
}
