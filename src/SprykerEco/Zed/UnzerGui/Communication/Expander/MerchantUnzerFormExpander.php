<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Expander;

use SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantUnzerFormExpander implements MerchantUnzerFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_MERCHANT_UNZER_PARTICIPANT_ID = 'merchantUnzerParticipantId';

    /**
     * @var \SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider
     */
    protected $merchantUnzerDataProvider;

    /**
     * @param \SprykerEco\Zed\UnzerGui\Communication\Form\DataProvider\MerchantUnzerFormDataProvider $merchantUnzerFormDataProvider
     */
    public function __construct(MerchantUnzerFormDataProvider $merchantUnzerFormDataProvider)
    {
        $this->merchantUnzerDataProvider = $merchantUnzerFormDataProvider;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $options = $this->getMerchantUnzerFormOptions($builder);

        return $this->addMerchantUnzerParticipantField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addMerchantUnzerParticipantField(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(static::FIELD_MERCHANT_UNZER_PARTICIPANT_ID, TextType::class, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return string[]
     */
    protected function getMerchantUnzerFormOptions(FormBuilderInterface $builder): array
    {
        $options = $this->merchantUnzerDataProvider->getOptions();

        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = $builder->getForm()->getData();
        $options['data'] = $this->merchantUnzerDataProvider->getData($merchantTransfer);

        return $options;
    }
}
