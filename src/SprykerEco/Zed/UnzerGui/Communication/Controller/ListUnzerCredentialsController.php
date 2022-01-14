<?php

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class ListUnzerCredentialsController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $unzerCredentialsTable = $this->getFactory()
            ->createUnzerCredentialsTable();

        return $this->viewResponse([
            'unzerCredentials' => $unzerCredentialsTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createUnzerCredentialsTable();

        return $this->jsonResponse($table->fetchData());
    }
}
