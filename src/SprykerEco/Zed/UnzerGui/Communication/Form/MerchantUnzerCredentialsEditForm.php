<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MerchantUnzerCredentialsEditForm extends MerchantUnzerCredentialsCreateForm
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_CURRENT_ID);
        $resolver->setRequired(static::CREDENTIALS_TYPE_CHOICES_OPTION);

        $resolver->setNormalizer('constraints', function (Options $options, $value) {
            return array_merge($value, [
                $this->getFactory()->createUnzerCredentialsConstraint(),
            ]);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdParentUnzerCredentials(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PARENT_ID_UNZER_CREDENTIALS, HiddenType::class);

        return $this;
    }
}