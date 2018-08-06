<?php
namespace kefu\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * UploadForm is the model behind the upload form.
 */
class ImportData extends Model{
	/**
	 * @var UploadedFile|Null file attribute
	 */
	public $upload;
        
	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [ 
			[['upload'], 'file'],
		];
	}
}
?>
