<?php
namespace app\models;

use yii\base\Model;
/**
 * UploadForm is the model behind the upload form.
 */
class ImportData extends Model
{
	/**
	 * @var UploadedFile|Null file attribute
	 */
	public $upload,$file,$title;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
//				[['upload'], 'file'],
                [['file'], 'file'],
                ['title', 'safe'],
		];
	}
}
?>