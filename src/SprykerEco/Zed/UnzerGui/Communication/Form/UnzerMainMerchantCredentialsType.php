<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerCredentialsTransfer;
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

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => UnzerCredentialsTransfer::class,
        ]);
    }

    /**
     * @return void
     */
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
