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
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;

class UnzerCredentialsTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const PARAM_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    public const COL_STORES = 'stores';

    /**
     * @var string
     */
    protected const STORE_CLASS_LABEL = 'label-info';

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
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME => 'Name',
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
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                SpyUnzerCredentialsTableMap::COL_CONFIG_NAME => $item[SpyUnzerCredentialsTableMap::COL_CONFIG_NAME],
                SpyUnzerCredentialsTableMap::COL_TYPE => $this->mapTypeName($item),
                SpyUnzerCredentialsStoreTableMap::COL_FK_STORE => $this->createStoresLabel($item),
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
            $results[] = $rowData;
        }

        return $results;
    }

    /**
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
    protected function mapTypeName(array $unzerCredentials): string
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
            UnzerGuiConfig::URL_MARKETPLACE_UNZER_CREDENTIALS_EDIT : UnzerGuiConfig::URL_STANDARD_UNZER_CREDENTIALS_EDIT;

        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate($editUrl, [static::PARAM_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS]]),
            'Edit',
        );
        $buttons[] = $this->generateButton(
            Url::generate(UnzerGuiConfig::URL_UNZER_CREDENTIALS_SYNC_PAYMENT_METHODS, [static::PARAM_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS]]),
            'Sync payment methods',
            [],
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(
                UnzerGuiConfig::URL_UNZER_CREDENTIALS_DELETE,
                [
                    static::PARAM_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                ],
            ),
            'Delete',
        );

        return implode(' ', $buttons);
    }
}
