<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use SprykerEco\Shared\Unzer\UnzerConstants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 */
class UnzerCredentialsCreateForm extends AbstractUnzerCredentialsForm
{
    /**
     * @var string
     */
    protected const FIELD_STORE_RELATION = 'storeRelation';

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
        $resolver->setRequired(static::MERCHANT_REFERENCE_CHOICES_OPTION);
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);

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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $choices = [])
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
    protected function addStoreRelationForm(FormBuilderInterface $builder)
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUnzerMainMerchantForm(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $event->getForm()->add(
                UnzerCredentialsTransfer::CHILD_UNZER_CREDENTIALS,
                UnzerMainMerchantCredentialsType::class,
                [
                    'label' => static::LABEL_MAIN_MERCHANT_CREDENTIALS,
                    self::MERCHANT_REFERENCE_CHOICES_OPTION => $builder->getOption(self::MERCHANT_REFERENCE_CHOICES_OPTION),
                ],
            );
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
            /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentials */
            $unzerCredentials = $event->getData();
            $unzerType = $unzerCredentials['type'] ?? null;
            $form = $event->getForm();

            if ((int)$unzerType !== UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) {
                $form->remove(UnzerCredentialsTransfer::CHILD_UNZER_CREDENTIALS);
            }
        });

        return $this;
    }
}
