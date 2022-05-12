<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use SprykerEco\Shared\Unzer\UnzerConstants;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class MerchantUnzerCredentialsCreateForm extends AbstractUnzerCredentialsForm
{
    /**
     * @var string
     */
    public const FIELD_PARENT_ID_UNZER_CREDENTIALS = 'parentIdUnzerCredentials';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_CURRENT_ID);
        $resolver->setRequired(static::CREDENTIALS_TYPE_CHOICES_OPTION);
        $resolver->setRequired(static::FIELD_PARENT_ID_UNZER_CREDENTIALS);
        $resolver->setRequired(static::MERCHANT_REFERENCE_CHOICES_OPTION);

        $resolver->setNormalizer('constraints', function (Options $options, $value) {
            return array_merge($value, [
                $this->getFactory()->createUnzerCredentialsConstraint(),
            ]);
        });
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'unzer-merchant-credentials';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdUnzerCredentialsField($builder)
            ->addNameField($builder)
            ->addUnzerKeypairType($builder)
            ->addMerchantReferenceField($builder, $options[static::MERCHANT_REFERENCE_CHOICES_OPTION])
            ->addParticipantIdField($builder)
            ->addIdParentUnzerCredentialsField($builder, $options)
            ->addTypeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIdParentUnzerCredentialsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PARENT_ID_UNZER_CREDENTIALS, HiddenType::class, [
            'data' => $options[static::FIELD_PARENT_ID_UNZER_CREDENTIALS],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $choices
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(UnzerCredentialsTransfer::MERCHANT_REFERENCE, Select2ComboBoxType::class, [
            'choices' => array_flip($choices),
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addParticipantIdField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::PARTICIPANT_ID, TextType::class, [
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::TYPE, HiddenType::class, [
            'data' => UnzerConstants::UNZER_CONFIG_TYPE_MARKETPLACE_MERCHANT,
            'required' => true,
        ]);

        return $this;
    }
}
