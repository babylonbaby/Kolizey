<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 05.12.16
 * Time: 15:11
 */
namespace Core\View;

//use Zend\View\Helper\AbstractHelper;
use StepForm\Form\View\Helper\FormButtonWrapper;
use StepForm\Form\Element\ButtonWrapper;
use Zend\Form\LabelAwareInterface;

class Buttons extends FormButtonWrapper
{
    /**
     * This is the default wrapper that the collection is wrapped into
     *
     * @var string
     */
    protected $wrapper = '<div%3$s>%2$s%1$s</div>';

    /**
     * If set to true, collections are automatically wrapped around a fieldset
     *
     * @var bool
     */
    protected $shouldWrap = true;

    /**
     * This is the default label-wrapper
     *
     * @var string
     */
    protected $labelWrapper = '<legend>%s</legend>';

    public function __invoke(ButtonWrapper $element)
    {
        $markup = parent::__invoke($element);
        // Every collection is wrapped by a fieldset if needed
        if ($this->shouldWrap) {
            $attributes = $element->getAttributes();
            unset($attributes['name']);
            $attributesString = count($attributes) ? ' ' . $this->createAttributesString($attributes) : '';

            $label = $element->getLabel();
            $legend = '';

            if (!empty($label)) {
                if (null !== ($translator = $this->getTranslator())) {
                    $label = $translator->translate(
                        $label,
                        $this->getTranslatorTextDomain()
                    );
                }

                if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                    $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                    $label = $escapeHtmlHelper($label);
                }

                $legend = sprintf(
                    $this->labelWrapper,
                    $label
                );
            }

            $markup = sprintf(
                $this->wrapper,
                $markup,
                $legend,
                $attributesString
            );
        }

        return $markup;
    }
} 