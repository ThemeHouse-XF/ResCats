<?php

class ThemeHouse_ResCats_ViewPublic_Category_Add extends XenForo_ViewPublic_Base
{

    public function renderHtml()
    {
        $buttonConfig = array(
            'basic' => true
        );
        
        $categoryDescription = XenForo_Html_Renderer_BbCode::renderFromHtml(
            $this->_params['category']['category_description']);
        
        /* @var $formatter ThemeHouse_ResCats_BbCode_Formatter_BbCode_Description */
        $formatter = XenForo_BbCode_Formatter_Base::create('ThemeHouse_ResCats_BbCode_Formatter_BbCode_Description');
        $formatter->configureForDescription();
        
        $parser = XenForo_BbCode_Parser::create($formatter);
        $categoryDescription = $parser->render($categoryDescription);
        
        $this->_params['descriptionEditor'] = XenForo_ViewPublic_Helper_Editor::getEditorTemplate($this, 
            'category_description', $categoryDescription, 
            array(
                'json' => array(
                    'buttonConfig' => $buttonConfig
                )
            ));
    }
}