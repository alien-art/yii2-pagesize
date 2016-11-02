<?php
/**
 * Виджет для вывода хлебных крошек
 */
namespace alien\PageSize;

use Yii;
use yii\helpers\Html;

class PageSize extends yii\base\Widget
{
	public $mPageSizeOptions = [10=>10, 25=>25, 50=>50, 75=>75, 100=>100];
	public $mPageSize = 10;
	public $mGridId = '';
	public $mDefPageSize = 10;
    public $Show = true;
	
	public function run()
	{			
		Yii::$app->session->set('pageSize', $this->mPageSize);
		
		$this->mPageSize = null == $this->mPageSize ? $this->mDefPageSize : $this->mPageSize;
		
		$content = Yii::t('pagesize', 'On the page: ');
		$content .= Html::dropDownList('pageSize', $this->mPageSize, $this->mPageSizeOptions,[
				'onchange'=>"$.pjax.reload({container:'#".$this->mGridId."', data:{pageSize: $(this).val() }})",
		]);
                
                if ($this->Show)
                    echo $content;
                else
                    return $content;
	}
        
        /**
	 * Инициализация
	 */
	public function init()
	{
            parent::init();
            $this->registerTranslations();
	}
     
        public function registerTranslations()
        {
            Yii::$app->i18n->translations['pagesize'] = 
            [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@alien/PageSize/messages',
                'forceTranslation' => true
            ];
        }
}
?>