<?php

namespace MmdAdmin\Game\Statistics\Form;

use Application\Form\SortableFieldset;
use Epos\Dao\Criterion\Filter;
use Mmd\Util\Form\QueryFilterForm;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;

class GameStatisticsFilterForm extends QueryFilterForm
{

    const ELEMENT_GAME   = 'name';
    const ELEMENT_SUBMIT = 'form_submit';
    const DEFAULT_LIMIT  = 25;

    public function init()
    {
        $this->add(
            [
                'name'    => static::ELEMENT_GAME,
                'type'    => 'Text',
                'options' => [
                    'label'        => 'Игра',
                    'empty_option' => '--Игра--',
                ],
            ]
        );

        $this->add(
            [
                'name' => Filter::SORT_PARAMETER_NAME,
                'type' => SortableFieldset::class,
            ]
        );

        $this->get(Filter::SORT_PARAMETER_NAME)
             ->setSortOptions(
                 [
                     'name'                                          => 'Имя',
                     'createdOn'                                     => 'Дата',
                     GameStatisticsSpecification::SPEC_GUILDS_COUNT  => 'Гильд.',
                     GameStatisticsSpecification::SPEC_MEMBERS_COUNT => 'Перс.',
                 ]
             );

        $this->add(
            [
                'type'       => 'Button',
                'name'       => static::ELEMENT_SUBMIT,
                'options'    => [
                    'label'  => 'Apply',
                    'ignore' => true,
                ],
                'attributes' => [
                    'type' => 'submit',
                ],
            ]
        );

        $this->setValidationGroup(
            static::ELEMENT_GAME,
            Filter::SORT_PARAMETER_NAME
        );

        return parent::init();
    }

    /**
     * Returns user specified input filter specification
     *
     * @return array
     */
    protected function getThisInputFilterSpecification()
    {
        return [
            ['name' => static::ELEMENT_GAME, 'required' => false],
            [
                'name'     => static::ELEMENT_SUBMIT,
                'required' => false,
                'filters'  => [$this->alwaysToNullFilterSpecification()],
            ],
        ];
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function filterValues($data)
    {
        if (empty($data[static::EL_LIMIT])) {
            $data[static::EL_LIMIT] = static::DEFAULT_LIMIT;
        }

        return parent::filterValues($data);
    }
}
