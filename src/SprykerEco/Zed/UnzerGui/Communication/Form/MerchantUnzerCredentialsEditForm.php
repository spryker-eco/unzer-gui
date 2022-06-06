<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class MerchantUnzerCredentialsEditForm extends MerchantUnzerCredentialsCreateForm
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_CURRENT_ID,
            static::CREDENTIALS_TYPE_CHOICES_OPTION,
            static::MERCHANT_REFERENCE_CHOICES_OPTION,
        ]);

        $resolver->setNormalizer('constraints', function (Options $options, $value) {
            return array_merge($value, [
                $this->getFactory()->createUnzerCredentialsConstraint(),
            ]);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIdParentUnzerCredentialsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(UnzerCredentialsTransfer::PARENT_ID_UNZER_CREDENTIALS, HiddenType::class);

        return $this;
    }
}
