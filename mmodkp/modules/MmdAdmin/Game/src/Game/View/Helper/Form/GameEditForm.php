<?php

namespace MmdAdmin\Game\View\Helper\Form;

use Application\View\Helper\Form\ElementErrorsTrait;
use Mmd\Game\Entity\Game;
use Mmd\Game\Form\GameEditForm as Form;
use Mmd\Game\InputFilter\GameEditFilter;
use Mmd\Thumbnail\Entity\Enum\ScaleEnum;
use Zend\Form\Element;
use Zend\View\Helper\AbstractHelper;

class GameEditForm extends AbstractHelper
{

    use ElementErrorsTrait;

    /**
     * @var Game
     */
    protected $object;

    public function __invoke(Form $form = null, $class = null)
    {
        if ($form) {
            return $this->render($form, $class);
        }

        return $this;
    }

    public function render(Form $form, $class)
    {
        $this->object = $form->getObject();

        $form->setAttribute('role', 'form');
        $form->setAttribute('class', $class);

        return $this->getView()->form()->openTag($form)
               . $this->getView()->formHidden($form->get(GameEditFilter::ELEMENT_ID))
               . '<div class="form-group">'
               . $this->renderNameElement($form->get(GameEditFilter::ELEMENT_NAME))
               . '</div>'
               . '<div class="form-group">'
               . $this->renderAliasElement($form->get(GameEditFilter::ELEMENT_ALIAS))
               . '</div>'
               . '<div class="form-group">'
               . $this->renderUploadLogoElement($form->get(GameEditFilter::ELEMENT_LOGO))
               . '</div>'
               . '<div class="form-group checkbox m-b-10 m-t-5">'
               . $this->renderPublicElement($form->get(GameEditFilter::ELEMENT_PUBLIC))
               . '</div>'
               . '<div class="form-group">'
               . $this->renderSubmitElement($form->get(GameEditFilter::ELEMENT_SUBMIT))
               . '</div>'
               . $this->getView()->form()->closeTag();
    }

    public function renderNameElement(Element $el)
    {
        return $this->renderTextInput($el);
    }

    public function renderAliasElement(Element $el)
    {
        return $this->renderTextInput($el);
    }

    public function renderPublicElement(Element $el)
    {
        $el->setAttribute('id', 'id-' . $el->getName());

        return $this->getView()->formCheckbox($el) . $this->getView()->formLabel($el);
    }

    public function renderUploadLogoElement(Element $el)
    {
        $preview = $el->getValue();

        if ($this->object->getLogo()) {
            $preview = $this->object->getLogo()->getThumbnail(ScaleEnum::VALUE_SMALL);
        }

        return $this->getView()->mmdSmallFileUpload()->setPreview($preview)->render($el)
             . $this->formElementErrors($el);
    }

    public function renderSubmitElement(Element $el)
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

}
