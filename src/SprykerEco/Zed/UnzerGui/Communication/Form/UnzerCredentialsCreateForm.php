<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 */
class UnzerCredentialsCreateForm extends AbstractType
{
    /**
     * @var string
     */
    public const CREDENTIALS_TYPE_CHOICES_OPTION = 'type_choices';

    /**
     * @var string
     */
    public const OPTION_CURRENT_ID = 'current_id';

    /**
     * @var string
     */
    protected const FIELD_ID_UNZER_CREDENTIALS = 'idUnzerCredentials';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'configName';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    protected const FIELD_STORE_RELATION = 'storeRelation';

    /**
     * @var string
     */
    protected const FIELD_MERCHANT_REFERENCE = 'merchantReference';

    /**
     * @var string
     */
    protected const FIELD_PARTICIPANT_ID = 'participantId';

    /**
     * @var string
     */
    protected const LABEL_NAME = 'Config name';

    /**
     * @var string
     */
    protected const LABEL_TYPE = 'Credentials type';

    /**
     * @var string
     */
    protected const LABEL_MAIN_MERCHANT_CREDENTIALS = 'Main merchant Credentials';

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
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'unzer-credentials';
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
            ->addTypeField($builder, $options[static::CREDENTIALS_TYPE_CHOICES_OPTION])
            ->addUnzerKeypairType($builder)
            ->addStoreRelationForm($builder)
            ->addUnzerMainMerchantForm($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdUnzerCredentialsField(FormBuilderInterface $builder): UnzerCredentialsCreateForm
    {
        $builder->add(static::FIELD_ID_UNZER_CREDENTIALS, HiddenType::class);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): UnzerCredentialsCreateForm
    {
        $builder->add(UnzerCredentialsTransfer::CONFIG_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $choices = []): UnzerCredentialsCreateForm
    {
        $builder->add(UnzerCredentialsTransfer::TYPE, ChoiceType::class, [
            'choices' => array_flip($choices),
            'required' => true,
            'label' => static::LABEL_TYPE,
            'placeholder' => 'Select one',

        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder): UnzerCredentialsCreateForm
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUnzerKeypairType(FormBuilderInterface $builder): UnzerCredentialsCreateForm
    {
        $builder->add(UnzerCredentialsTransfer::UNZER_KEYPAIR, UnzerKeypairType::class);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUnzerMainMerchantForm(FormBuilderInterface $builder): UnzerCredentialsCreateForm
    {
        $builder->add(
            UnzerCredentialsTransfer::CHILD_UNZER_CREDENTIALS,
            UnzerMainMerchantCredentialsType::class, [
                'label' => static::LABEL_MAIN_MERCHANT_CREDENTIALS,
                'required' => false
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }
}
