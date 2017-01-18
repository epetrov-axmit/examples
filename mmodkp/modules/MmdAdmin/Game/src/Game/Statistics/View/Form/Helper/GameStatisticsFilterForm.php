<?php

namespace MmdAdmin\Game\Statistics\View\Form\Helper;

use Mmd\Util\View\Form\Helper\AbstractForm;
use Zend\Form\Form;
use MmdAdmin\Game\Statistics\Form\GameStatisticsFilterForm as FilterForm;

/**
 * Class GameStatisticsFilterForm
 *
 * @package MmdAdmin\Game\Statistics\View\Form\Helper
 */
class GameStatisticsFilterForm extends AbstractForm
{
    /**
     * @param Form $form
     *
     * @return Form
     */
    protected function prepare(Form $form)
    {
        $form->setAttribute('method', 'GET');
        $form->setAttribute('class', 'form-columned');
        $form->setAttribute('role', 'form');
        $game = $form->get(FilterForm::ELEMENT_GAME);
        $game->setAttribute('class', 'form-control input-sm');
        $game->setAttribute('placeholder', $game->getLabel());
        $submit = $form->get(FilterForm::ELEMENT_SUBMIT);
        $submit->setAttribute('class', 'btn btn-sm');
    }
}
