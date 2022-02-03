<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteMerchantUnzerCredentialsController extends AbstractMerchantUnzerCredentialsController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idUnzerCredentials = $this->castId($request->get(static::PARAM_ID_UNZER_CREDENTIALS));
        $parentIdUnzerCredentials = $this->castId($request->get(static::PARAM_PARENT_ID_UNZER_CREDENTIALS));

        /** @var \Generated\Shared\Transfer\UnzerCredentialsTransfer $unzerCredentialsTransfer */
        $unzerCredentialsTransfer = $this->getFactory()->createUnzerCredentialsFormDataProvider()->getData($idUnzerCredentials);

        $unzerCredentialsResponseTransfer = $this->getFactory()
            ->getUnzerFacade()
            ->deleteUnzerCredentials($unzerCredentialsTransfer);

        if (!$unzerCredentialsResponseTransfer->getIsSuccessful()) {
            foreach ($unzerCredentialsResponseTransfer->getMessages() as $message) {
                $this->addErrorMessage($message->getMessage());
            }
        } else {
            $this->addSuccessMessage(static::MESSAGE_UNZER_CREDENTIALS_DELETE_SUCCESS);
        }

        return $this->redirectResponse($this->buildRedirectUrl($parentIdUnzerCredentials));
    }
}
