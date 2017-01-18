<?php

namespace MmdAdmin\Game\View\Helper\Form;

use Application\View\Helper\Form\ElementErrorsTrait;
use Mmd\Game\InputFilter\ServerEditFilter;
use Zend\Form\Element;
use Zend\View\Helper\AbstractHelper;
use Mmd\Game\Form\ServerEditForm as Form;

/**
 * Class ServerEditForm
 *
 * @package MmdAdmin\Game\View\Helper\Form
 */
class ServerEditForm extends AbstractHelper
{

    use ElementErrorsTrait;

    public function __invoke(Form $form = null, $class = null)
    {
        if ($form) {
            return $this->render($form, $class);
        }
        return $this;
    }

    public function render(Form $form, $class)
    {
        $form->setAttribute('role', 'form');
        $form->setAttribute('class', $class);

        return $this->getView()->form()->openTag($form)
        . $this->getView()->formHidden($form->get(ServerEditFilter::ELEMENT_ID))
        . '<div class="form-group">'
        . $this->renderTextInput($form->get(ServerEditFilter::ELEMENT_NAME))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderTextInput($form->get(ServerEditFilter::ELEMENT_URL))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderSelectInput($form->get(ServerEditFilter::ELEMENT_GAME))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderMarkdownEditor($form->get(ServerEditFilter::ELEMENT_DESCRIPTION))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderTextInput($form->get(ServerEditFilter::ELEMENT_RATES))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderTextInput($form->get(ServerEditFilter::ELEMENT_POPULATION))
        . '</div>'
        . '<div class="form-group checkbox m-b-10 m-t-5">'
        . $this->renderPublicElement($form->get(ServerEditFilter::ELEMENT_PUBLIC))
        . '</div>'
        . '<div class="form-group">'
        . $this->renderSubmitElement($form->get(ServerEditFilter::ELEMENT_SUBMIT))
        . '</div>'
        . $this->getView()->form()->closeTag();
    }

    protected function renderMarkdownEditor(Element $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'form-control');
        $el->setAttribute('rows', '6');
        return $this->getView()->formElement($el) . $this->formElementErrors($el);
    }

    protected function renderPublicElement(Element $el)
    {
        $el->setAttribute('id', 'id-' . $el->getName());
        return $this->getView()->formCheckbox($el) . $this->getView()->formLabel($el);
    }

    protected function renderSubmitElement(Element $el)
    {
        $el->setAttribute('class', 'btn');
        return $this->getView()->formElement($el);
    }

    protected function renderTextInput(Element $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'form-control');
        return $this->getView()->formElement($el) . $this->formElementErrors($el);
    }

    protected function renderSelectInput(Element $el)
    {
        $el->setAttribute('placeholder', $el->getLabel());
        $el->setAttribute('class', 'select');
        return $this->getView()->formElement($el) . $this->formElementErrors($el);
    }

}
