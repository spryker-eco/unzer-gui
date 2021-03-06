<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class UnzerMainMerchantCredentialsType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_ID_UNZER_CREDENTIALS = 'idUnzerCredentials';

    /**
     * @var string
     */
    protected const LABEL_MERCHANT_REFERENCE = 'Merchant Reference';

    /**
     * @var string
     */
    protected const LABEL_PARTICIPANT_ID = 'Participant Id';

    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Form\AbstractUnzerCredentialsForm::MERCHANT_REFERENCE_CHOICES_OPTION
     */
    protected const MERCHANT_REFERENCE_CHOICES_OPTION = 'merchant_reference_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(static::MERCHANT_REFERENCE_CHOICES_OPTION);

        $resolver->setDefaults([
            'data_class' => UnzerCredentialsTransfer::class,
        ]);
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
            ->addMerchantReferenceField($builder, $options[static::MERCHANT_REFERENCE_CHOICES_OPTION])
            ->addParticipantIdField($builder)
            ->addTypeField($builder)
            ->addUnzerKeypairTypeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdUnzerCredentialsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_UNZER_CREDENTIALS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUnzerKeypairTypeField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::UNZER_KEYPAIR, UnzerKeypairType::class);

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
            'help' => 'Leave empty if main seller is not a merchant',
            'choices' => array_flip($choices),
            'multiple' => false,
            'required' => false,
            'label' => static::LABEL_MERCHANT_REFERENCE,
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
            'label' => static::LABEL_PARTICIPANT_ID,
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
        $builder->add(UnzerCredentialsTransfer::TYPE, HiddenType::class);

        return $this;
    }
}
