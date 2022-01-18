<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use FFI\Exception;
use Generated\Shared\Transfer\UnzerCredentialsTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Unzer\UnzerConstants;
use SprykerEco\Zed\Unzer\Business\Exception\UnzerException;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class CreateUnzerCredentialsController extends AbstractController
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const MESSAGE_CREDENTIALS_CREATE_SUCCESS = 'Unzer Credentials created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $unzerCredentialsFormDataProvider = $this->getFactory()->createUnzerCredentialsFormDataProvider();
        $unzerCredentialsForm = $this->getFactory()
            ->getUnzerCredentialsCreateForm(
                $unzerCredentialsFormDataProvider->getData(),
                $unzerCredentialsFormDataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($unzerCredentialsForm->isSubmitted() && $unzerCredentialsForm->isValid()) {
            return $this->createUnzerCredentials($request, $unzerCredentialsForm);
        }

        return $this->viewResponse([
            'form' => $unzerCredentialsForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $unzerCredentialsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function createUnzerCredentials(Request $request, FormInterface $unzerCredentialsForm)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, UnzerGuiConfig::URL_UNZER_CREDENTIALS_LIST);

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $unzerCredentialsForm->getData();

        $propelConnection = Propel::getConnection();

        try {
            $propelConnection->beginTransaction();
            $unzerCredentialsTransfer = $this->saveUnzerCredentials($unzerCredentialsTransfer);
            if ($unzerCredentialsTransfer->getType() === UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) {
                $childUnzerCredentialsTransfer = $unzerCredentialsTransfer->getChildUnzerCredentialsOrFail();
                $childUnzerCredentialsTransfer->setParentIdUnzerCredentials($unzerCredentialsTransfer->getIdUnzerCredentials());
                $childUnzerCredentialsTransfer->setType(UnzerConstants::UNZER_CONFIG_TYPE_MARKETPLACE_MAIN_MERCHANT);

                $this->saveUnzerCredentials($childUnzerCredentialsTransfer);
            }

//            $this->getFactory()->getUnzerFacade()->setUnzerNotificationUrl($unzerCredentialsTransfer);
        } catch (Exception $exception) {
            $propelConnection->rollBack();
            $this->addErrorMessage($exception->getMessage());

            return $this->viewResponse([
                'form' => $unzerCredentialsForm->createView(),
            ]);
        }

        $propelConnection->commit();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer
     *
     * @throws \SprykerEco\Zed\Unzer\Business\Exception\UnzerException
     */
    protected function saveUnzerCredentials(UnzerCredentialsTransfer $unzerCredentialsTransfer): UnzerCredentialsTransfer
    {
        $unzerCredentialsResponseTransfer = $this->getFactory()->getUnzerFacade()->createUnzerCredentials($unzerCredentialsTransfer);
        if ($unzerCredentialsResponseTransfer->getIsSuccessful()) {
            return $unzerCredentialsResponseTransfer->getUnzerCredentials();
        }

        throw new UnzerException($unzerCredentialsResponseTransfer->getMessages()[0]);
    }
}
