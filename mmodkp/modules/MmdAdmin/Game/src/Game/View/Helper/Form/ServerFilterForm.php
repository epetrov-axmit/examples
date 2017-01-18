<?php

namespace MmdAdmin\Game\View\Helper\Form;

use Application\Form\SortableFieldset;
use Mmd\Game\Form\ServerFilterForm as Form;
use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ServerFilterForm
 *
 * @package MmdAdmin\Game\View\Helper\Form
 */
class ServerFilterForm extends AbstractHelper
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
        $form->setAttribute('class', 'row form-columned m-b-10');
        $form->setAttribute('role', 'form');
        $form->setAttribute('method', 'GET');

        return $this->getView()->form()->openTag($form)
        . '<div class="col-md-2">'
        . $this->renderText($form->get(Form::ELEMENT_NAME))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->renderText($form->get(Form::ELEMENT_URL))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->renderSelectInput($form->get(Form::ELEMENT_GAME))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->getView()->mmdRadioButtonGroup($form->get(Form::SORT_PARAMETER_NAME)->get(SortableFieldset::EL_FIELD))
        . '</div>'
        . '<div class="col-md-2">'
        . $this->getView()->mmdRadioButtonGroup(
            $form->get(Form::SORT_PARAMETER_NAME)->get(SortableFieldset::EL_DIRECTION)
        )
        . '</div>'
        . '<div class="col-md-2">'
        . $this->renderSubmit($form->get(Form::ELEMENT_SUBMIT))
        . '</div>'
        . $this->getView()->form()->closeTag();
    }

    protected function renderText(ElementInterface $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'form-control input-sm m-r-5');
        return $this->getView()->formElement($el);
    }

    protected function renderSelectInput(ElementInterface $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'select');
        return $this->getView()->formElement($el);
    }

    protected function renderSubmit(ElementInterface $el)
    {
        $el->setAttribute('class', 'btn btn-sm pull-right');

        $markup = $this->getView()->formButton($el)
            . '<a href="' . $this->form->getAttribute('action') . '" class="btn btn-sm pull-right m-r-5">'
            . $this->getView()->translate('Сбросить') . '</a>';

        return $markup;
    }

}
