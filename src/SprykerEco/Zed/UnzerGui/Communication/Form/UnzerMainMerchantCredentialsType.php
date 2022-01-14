<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnzerMainMerchantCredentialsType extends AbstractType
{
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
    protected const FIELD_ID_UNZER_CREDENTIALS = 'idUnzerCredentials';

    /**
     * @var string
     */
    protected const LABEL_MERCHANT_REFERENCE = 'Merchant Reference';

    /**
     * @var string
     */
    protected const LABEL_PARTICIPANT_ID = 'Participant Id';

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => UnzerCredentialsTransfer::class
        ]);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdUnzerCredentialsField($builder)
            ->addMerchantReferenceField($builder)
            ->addParticipantIdField($builder)
            ->addTypeField($builder)
            ->addUnzerKeypairType($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     * @return $this
     */
    protected function addIdUnzerCredentialsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_UNZER_CREDENTIALS, HiddenType::class);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUnzerKeypairType(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::UNZER_KEYPAIR, UnzerKeypairType::class);

        return $this;
    }

    protected function addMerchantReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::MERCHANT_REFERENCE, TextType::class, [
            'required' => true,
        ]);

        return $this;
    }

    protected function addParticipantIdField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::PARTICIPANT_ID, TextType::class, [
            'required' => true,
        ]);

        return $this;
    }

    protected function addTypeField(FormBuilderInterface $builder)
    {
        $builder->add(UnzerCredentialsTransfer::TYPE, HiddenType::class);

        return $this;
    }
}
