<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\UnzerGui\Communication\UnzerGuiCommunicationFactory getFactory()
 */
class SuggestMerchantController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_TERM = 'term';

    /**
     * @var string
     */
    protected const KEY_RESULTS = 'results';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $response = $this->executeIndexAction($request);

        return $this->jsonResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, array<string, string>>
     */
    protected function executeIndexAction(Request $request): array
    {
        $suggestionName = $request->query->get(static::PARAM_TERM);

        $merchantCriteriaTransfer = $this->createMerchantCriteriaTransfer($suggestionName);

        $merchantCollectionTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->get($merchantCriteriaTransfer);

        $formattedMerchantList = $this->getFactory()
            ->createUnzerGuiFormatter()
            ->formatMerchantCollectionTransferToSuggestionsArray($merchantCollectionTransfer);

        return [
            static::KEY_RESULTS => $formattedMerchantList,
        ];
    }

    /**
     * @param string $suggestionName
     *
     * @return \Generated\Shared\Transfer\MerchantCriteriaTransfer
     */
    protected function createMerchantCriteriaTransfer(string $suggestionName): MerchantCriteriaTransfer
    {
        $limit = $this->getFactory()->getConfig()->getMerchantSuggestionLimit();

        return (new MerchantCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setLimit($limit))
            ->addMerchantReference($suggestionName);
    }
}
