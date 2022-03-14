<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class UnzerCredentialsEditForm extends UnzerCredentialsCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, int> $choices
     *
     * @return $this
     */
    protected function addTypeField(
        FormBuilderInterface $builder,
        array $choices = []
    ): UnzerCredentialsEditForm
    {
        $builder->add(UnzerCredentialsTransfer::TYPE, ChoiceType::class, [
            'choices' => array_flip($choices),
            'disabled' => true,
            'label' => static::LABEL_TYPE,
            'placeholder' => 'Select one',
        ]);

        return $this;
    }
}
