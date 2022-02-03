<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\UnzerGui\Communication\Table;

use Orm\Zed\Unzer\Persistence\Map\SpyUnzerCredentialsTableMap;
use Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerEco\Shared\Unzer\UnzerConstants;
use SprykerEco\Zed\UnzerGui\UnzerGuiConfig;

class AnotherMerchantUnzerCredentialsTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const REQUEST_ID_UNZER_CREDENTIALS = 'id-unzer-credentials';

    /**
     * @var string
     */
    protected const REQUEST_PARENT_ID_UNZER_CREDENTIALS = 'parent-id-unzer-credentials';

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
     * @var string
     */
    protected $merchantReference;

    /**
     * @param \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery $unzerCredentialsQuery
     * @param string $merchantReference
     */
    public function __construct(
        SpyUnzerCredentialsQuery $unzerCredentialsQuery,
        string $merchantReference
    ) {
        $this->unzerCredentialsQuery = $unzerCredentialsQuery;
        $this->merchantReference = $merchantReference;
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
        ]);

        $config->setSearchable([
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);
        $config->setDefaultSortField(SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS, TableConfiguration::SORT_DESC);

        if ($this->getIdUnzerCredentials()) {
            $tableUrl = Url::generate($this->defaultUrl, [static::REQUEST_ID_UNZER_CREDENTIALS => $this->getIdUnzerCredentials()]);
            $config->setUrl($tableUrl);
        }

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
            SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS => 'ID',
            SpyUnzerCredentialsTableMap::COL_CONFIG_NAME => 'Config name',
            SpyUnzerCredentialsTableMap::COL_PUBLIC_KEY => 'Public Key',
            SpyUnzerCredentialsTableMap::COL_PARTICIPANT_ID => 'Participant ID',
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
                SpyUnzerCredentialsTableMap::COL_PUBLIC_KEY => $item[SpyUnzerCredentialsTableMap::COL_PUBLIC_KEY],
                SpyUnzerCredentialsTableMap::COL_PARTICIPANT_ID => $item[SpyUnzerCredentialsTableMap::COL_PARTICIPANT_ID],
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
            $results[] = $rowData;
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @return \Orm\Zed\Unzer\Persistence\SpyUnzerCredentialsQuery
     */
    protected function prepareQuery(): SpyUnzerCredentialsQuery
    {
//        $this->unzerCredentialsQuery
//            ->leftJoin(
//                $this->unzerCredentialsQuery
//                    ->filterByType(4)
//                    ->filterByMerchantReference($this->merchantReference)
//                    ->select()
//            )->addJoinCondition()
//            ->leftJoinUnzerCredentialsStore()

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
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(
                UnzerGuiConfig::URL_MERCHANT_UNZER_CREDENTIALS_EDIT,
                [
                    static::REQUEST_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                    static::REQUEST_PARENT_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_PARENT_ID_UNZER_CREDENTIALS],
                ],
            ),
            'Edit',
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(
                UnzerGuiConfig::URL_MERCHANT_UNZER_CREDENTIALS_DELETE,
                [
                    static::REQUEST_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_ID_UNZER_CREDENTIALS],
                    static::REQUEST_PARENT_ID_UNZER_CREDENTIALS => $item[SpyUnzerCredentialsTableMap::COL_PARENT_ID_UNZER_CREDENTIALS],
                ],
            ),
            'Delete',
        );

        return implode(' ', $buttons);
    }

    /**
     * @return int
     */
    protected function getIdUnzerCredentials(): int
    {
        return $this->request->query->getInt(static::REQUEST_ID_UNZER_CREDENTIALS, 0);
    }
}
