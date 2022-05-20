<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Table;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Unzer\Persistence\Map\SpyUnzerCredentialsStoreTableMap;
use Orm\Zed\Unzer\Persistence\Map\SpyUnzerCredentialsTableMap;
use Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerEco\Shared\Unzer\UnzerConstants;

class UnzerCredentialsTable extends AbstractTable
{
    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\SyncPaymentMethodsController::indexAction()
     */
    protected const ROUTE_UNZER_CREDENTIALS_SYNC_PAYMENT_METHODS = '/unzer-gui/sync-payment-methods';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_STORES = 'stores';

    /**
     * @var string
     */
    protected const STORE_CLASS_LABEL = 'label-info';

    /**
     * @var string
     *
     * @uses \SprykerEco\Zed\UnzerGui\Communication\Controller\AbstractUnzerCredentialsController::PARAMETER_ID_UNZER_CREDENTIALS
     */
    public const PARAMETER_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

    /**
     * @var string
     */
    protected const ROUTE_UNZER_CREDENTIALS_DELETE = '/unzer-gui/delete-unzer-credentials';

    /**
     * @var string
     */
    protected const ROUTE_MARKETPLACE_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-marketplace-unzer-credentials';

    /**
     * @var string
     */
    protected const ROUTE_STANDARD_UNZER_CREDENTIALS_EDIT = '/unzer-gui/edit-standard-unzer-credentials';

    /**
     * @var \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery
     */
    protected $unzerCredentialsQuery;

    /**
     * @param \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery $unzerCredentialsQuery
     */
    public function __construct(SpyUnzerCredentialsQuery $unzerCredentialsQuery)
    {
        $this->unzerCredentialsQuery = $unzerCredentialsQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS,
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME,
            SpyUnzerCredentialsTableMap::COL_TYPE,
        ]);

        $config->setSearchable([
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            SpyUnzerCredentialsTableMap::COL_TYPE,
            SpyUnzerCredentialsStoreTableMap::COL_FK_STORE,
        ]);
        $config->setDefaultSortField(SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setHeader([
            SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS => 'Id',
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME => 'Credentials List Id',
            SpyUnzerCredentialsTableMap::COL_TYPE => 'Credentials type',
            SpyUnzerCredentialsStoreTableMap::COL_FK_STORE => 'Available in Stores',
            static::COL_ACTIONS => 'Actions',
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $unzerCredentials = [];

        foreach ($queryResults as $queryResultItem) {
            $unzerCredentialsItem = [
                SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS => $queryResultItem[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                SpyUnzerCredentialsTableMap::COL_CONFIG_NAME => $queryResultItem[SpyUnzerCredentialsTableMap::COL_CONFIG_NAME],
                SpyUnzerCredentialsTableMap::COL_TYPE => $this->createTypeName($queryResultItem),
                SpyUnzerCredentialsStoreTableMap::COL_FK_STORE => $this->createStoresLabel($queryResultItem),
                static::COL_ACTIONS => $this->buildLinks($queryResultItem),
            ];
            $unzerCredentials[] = $unzerCredentialsItem;
        }

        return $unzerCredentials;
    }

    /**
     * @module Store
     *
     * @return \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery
     */
    protected function prepareQuery(): SpyUnzerCredentialsQuery
    {
        $this->unzerCredentialsQuery
            ->groupByIdUnzerCredentials()
            ->filterByType_In([UnzerConstants::UNZER_CONFIG_TYPE_STANDARD, UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE])
            ->useUnzerCredentialsStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinStore()
                ->withColumn(
                    sprintf('GROUP_CONCAT(%s)', SpyStoreTableMap::COL_NAME),
                    static::COL_STORES,
                )
            ->endUse();

        return $this->unzerCredentialsQuery;
    }

    /**
     * @param array $unzerCredentials
     *
     * @return string
     */
    protected function createTypeName(array $unzerCredentials): string
    {
        $currentType = $unzerCredentials[SpyUnzerCredentialsTableMap::COL_TYPE];

        return UnzerConstants::UNZER_CONFIG_TYPES[$currentType] ?? '';
    }

    /**
     * @param array $unzerCredentials
     *
     * @return string
     */
    protected function createStoresLabel(array $unzerCredentials): string
    {
        $storeNames = explode(',', $unzerCredentials[static::COL_STORES]);

        $storeLabels = [];
        foreach ($storeNames as $storeName) {
            $storeLabels[] = $this->generateLabel($storeName, static::STORE_CLASS_LABEL);
        }

        return implode(' ', $storeLabels);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $editUrl = ($item[SpyUnzerCredentialsTableMap::COL_TYPE] === UnzerConstants::UNZER_CONFIG_TYPE_MAIN_MARKETPLACE) ?
            static::ROUTE_MARKETPLACE_UNZER_CREDENTIALS_EDIT : static::ROUTE_STANDARD_UNZER_CREDENTIALS_EDIT;

        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate($editUrl, [static::PARAMETER_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS]]),
            'Edit',
        );
        $buttons[] = $this->generateButton(
            Url::generate(static::ROUTE_UNZER_CREDENTIALS_SYNC_PAYMENT_METHODS, [static::PARAMETER_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS]]),
            'Sync payment methods',
            [],
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(
                static::ROUTE_UNZER_CREDENTIALS_DELETE,
                [
                    static::PARAMETER_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                ],
            ),
            'Delete',
        );

        return implode(' ', $buttons);
    }
}
