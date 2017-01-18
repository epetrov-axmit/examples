<?php

namespace MmdAdmin\Game\View\Helper\Form;

use Application\Form\SortableFieldset;
use Mmd\Game\Form\GameFilterForm as Form;
use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GameFilterForm
 *
 * @package MmdAdmin\Game\View\Helper\Form
 */
class GameFilterForm extends AbstractHelper
{

    /**
     * @var Form
     */
    protected $form;

    public function __invoke(Form $form)
    {
        if ($form) {
            $this->form = $form;
            return $this->render($form);
        }

        return $this;
    }

    public function render(Form $form)
    {
        $form->setAttribute('class', 'form-inline m-b-10');
        $form->setAttribute('role', 'form');
        $form->setAttribute('method', 'GET');

        return $this->getView()->form()->openTag($form)
        . '<div class="col-md-2">'
        . $this->renderText($form->get(Form::EL_NAME))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->renderText($form->get(Form::EL_ALIAS))
        . '</div>'
        . '<div class="col-md-6">'
        . $this->getView()->mmdRadioButtonGroup($form->get(Form::SORT_PARAMETER_NAME)->get(SortableFieldset::EL_FIELD))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->getView()->mmdRadioButtonGroup(
            $form->get(Form::SORT_PARAMETER_NAME)->get(SortableFieldset::EL_DIRECTION)
        )
        . '</div>'
        . $this->renderSubmit($form->get(Form::EL_SUBMIT))
        . $this->getView()->form()->closeTag();
    }

    public function renderText(ElementInterface $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'form-control input-sm m-r-5');
        return $this->getView()->formElement($el);
    }

    public function renderSubmit(ElementInterface $el)
    {
        $el->setAttribute('class', 'btn btn-sm pull-right');

        $markup = $this->getView()->formButton($el)
            . '<a href="' . $this->form->getAttribute('action') . '" class="btn btn-sm pull-right m-r-5">'
            . $this->getView()->translate('Сбросить') . '</a>';

        return $markup;
    }

}