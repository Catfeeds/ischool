<?php
/**
 * 生成excel文件操作
 *
 * @author wesley wu
 * @date 2013.12.9
 */
class Excelses
{

    private $limit = 10000;
    public function download($data,$name)
    {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$name.xls");
        $guard = 0;
        foreach($data as $v)
        {
            $guard++;
            if($guard==$this->limit)
            {
                ob_flush();
                flush();
                $guard = 0;
            }
            echo $this->_addRow($v);
        }
    }

    private function _addRow($row)
    {
        $cells = "";
        foreach ($row as $k => $v)
        {
            $cells .= $v ."\t";
        }
        return   $cells . "\n";
    }
}