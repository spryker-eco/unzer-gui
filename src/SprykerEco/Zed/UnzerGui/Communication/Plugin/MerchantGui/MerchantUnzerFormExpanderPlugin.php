<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Plugin\MerchantGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class MerchantUnzerFormExpanderPlugin extends AbstractPlugin implements MerchantFormExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_MERCHANT_UNZER_PARTICIPANT_ID = 'merchantUnzerParticipantId';

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
        $this->addMerchantUnzerParticipantField($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantUnzerParticipantField(FormBuilderInterface $builder)
    {
        $options = $this->getMerchantUnzerFormOptions($builder);

        $builder->add(static::FIELD_MERCHANT_UNZER_PARTICIPANT_ID, TextType::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return string[]
     */
    protected function getMerchantUnzerFormOptions(FormBuilderInterface $builder): array
    {
        $merchantUnzerDataProvider = $this->getFactory()
            ->createMerchantUnzerFormDataProvider();

        $options = $merchantUnzerDataProvider->getOptions();

        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = $builder->getForm()->getData();
        $options['data'] = $merchantUnzerDataProvider->getData($merchantTransfer);

        return $options;
    }
}
