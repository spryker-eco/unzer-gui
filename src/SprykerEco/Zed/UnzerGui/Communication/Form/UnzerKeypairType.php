<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Form;

use Generated\Shared\Transfer\UnzerKeypairTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Zed\UnzerGui\UnzerGuiConfig getConfig()
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class UnzerKeypairType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_PUBLIC_KEY = 'publicKey';

    /**
     * @var string
     */
    protected const FIELD_PRIVATE_KEY = 'privateKey';

    /**
     * @var string
     */
    protected const FIELD_KEYPAIR_ID = 'keypairId';

    /**
     * @var string
     */
    protected const LABEL_PUBLIC_KEY = 'Unzer Public Key';

    /**
     * @var string
     */
    protected const LABEL_PRIVATE_KEY = 'Unzer Private Key';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => UnzerKeypairTransfer::class,
            'label' => false,
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
            ->addPublicKeyField($builder)
            ->addPrivateKeyField($builder)
            ->addKeypairIdField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPublicKeyField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_PUBLIC_KEY, TextType::class, [
                'label' => static::LABEL_PUBLIC_KEY,
                'constraints' => $this->getTextFieldConstraints(),
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPrivateKeyField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_PRIVATE_KEY, TextType::class, [
                'label' => static::LABEL_PRIVATE_KEY,
                'constraints' => $this->getTextFieldConstraints(),
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKeypairIdField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_KEYPAIR_ID, HiddenType::class);

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
