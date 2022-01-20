<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->addMerchantReferenceField($builder)
            ->addParticipantIdField($builder)
            ->addIdParentUnzerCredentials($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdParentUnzerCredentials(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PARENT_ID_UNZER_CREDENTIALS, HiddenType::class, [
            'data' => $options[static::FIELD_PARENT_ID_UNZER_CREDENTIALS],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::MERCHANT_REFERENCE, TextType::class, [
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
}
