<?php

/**
 * Виджет для переключателя страниц и выобора количества записей на странице
 */

namespace alien\PageSize;

use Yii;
use yii\helpers\Html;

class PageSize extends \yii\widgets\LinkPager {

    /**
     * {pageButtons} {customPage} {pageSize}
     * @var type string
     */
    public $template = '<div style="white-space: nowrap;"><div style="display: inline-block;">{pageSize}</div><div style="display: inline-block;">{pageButtons}</div></div>',

    /**
     *
     * @var type array
     */
    public $pageSizeList = [''=>'Все',10=>10,20=>20,50=>50,100=>100,200=>200];

    /**
     *
     * Margin style for the  pageSize control
     */
    public $pageSizeMargin = "margin-left:5px;margin-right:5px;";

    /**
     * customPage width
     */
    public $customPageWidth = 50;

    /**
     * Margin style for the  customPage control
     */
    public $customPageMargin = "margin-left:5px;margin-right:5px;";

    /**
     * Jump
     */
    public $customPageBefore = '';

    /**
     * Page
     */
    public $customPageAfter = "";

    /**
     * pageSize style
     */
    public $pageSizeOptions = ['class' => 'form-control', 'style' => 'display: inline-block;width:auto;margin-top:0px;'];

    /**
     * customPage style
     */
    public $customPageOptions = ['class' => 'form-control', 'style' => 'display: inline-block;margin-top:0px;'];

    /**
     * Инициализация
     */
    public function init() {
        parent::init();
        $this->registerTranslations();
        if ($this->pageSizeMargin) {
            Html::addCssStyle($this->pageSizeOptions, $this->pageSizeMargin);
        }
        if ($this->customPageWidth) {
            Html::addCssStyle($this->customPageOptions, 'width:' . $this->customPageWidth . 'px;');
        }
        if ($this->customPageMargin) {
            Html::addCssStyle($this->customPageOptions, $this->customPageMargin);
        }
    }

    public function registerTranslations() {
        Yii::$app->i18n->translations['pagesize'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@alien/PageSize/messages',
            'forceTranslation' => true
        ];
    }

    public function run() {

        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageContent();
        //Yii::$app->session->set('pageSize', $this->mPageSize);
       /* $this->mPageSize = ($this->mPageSize == null) ? $this->mDefPageSize : $this->mPageSize;

        $content = Yii::t('pagesize', 'On the page: ');
        $content .= Html::dropDownList('pageSize', $this->mPageSize, $this->mPageSizeOptions, [
                    'onchange' => ($this->pjaxEnable) ? "$.pjax.reload({container:'#" . $this->mGridId . "', data:{pageSize: $(this).val() }})" : "location += (location.search ? \"&\" : \"?\") + \"pageSize=\"+$(this).val();",
                    'class' => 'form-control',
        ]);

        if ($this->Show)
            echo $content;
        else
            return $content;*/
    }

    protected function renderPageContent() {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if ('customPage' == $name) {
                return $this->renderCustomPage();
            } else if ('pageSize' == $name) {
                return $this->renderPageSize();
            } else if ('pageButtons' == $name) {
                return $this->renderPageButtons();
            }
            return "";
        }, $this->template);
    }

    protected function renderPageSize() {
        return Yii::t('pagesize', 'On the page: ').Html::dropDownList($this->pagination->pageSizeParam, $this->pagination->getPageSize(), $this->pageSizeList, $this->pageSizeOptions);
    }

    protected function renderCustomPage() {
        $page = 1;
        $params = Yii::$app->getRequest()->queryParams;
        if (isset($params[$this->pagination->pageParam])) {
            $page = intval($params[$this->pagination->pageParam]);
            if ($page < 1) {
                $page = 1;
            } else if ($page > $this->pagination->getPageCount()) {
                $page = $this->pagination->getPageCount();
            }
        }
        return $this->customPageBefore . Html::textInput($this->pagination->pageParam, $page, $this->customPageOptions) . $this->customPageAfter;
    }

}

?>