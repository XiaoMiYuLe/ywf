<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_PChart
 * @subpackage Zeed_PChart_Data
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id$
 */

require_once ZEED_PATH_3rd . 'pChart/pData.php';

/**
 * pChart 数据源设置
 *
 * @author Nroe
 */
class Zeed_PChart_Data extends pData
{
    /**
     * 设置 pChart 数据源
     *
     * 设置 a,b,c 为数据源，d 不选择，1 作为 X 轴的注释
     * <code>
     * Zeed_PChart_Data::addData('1,a,b,c,d', array(1,2,3));
     * </code>
     *
     * @param string $data pChart 数据，格式为 CSV 格式, 1,a,b,c
     * @param string $delimiter 数据分隔符
     * @param array $dataColumns 数据选择，可以传递一个数组来指定指定数据所在位置
     */
    public function addData($data, array $dataColumns, $delimiter = ',')
    {
        $serieName = '';
        $values = explode($delimiter, $data);

        if (! empty($values[0])) {
            $serieName = $values[0];
        }

        foreach ($dataColumns as $key) {
            if (isset($values[$key])) {
                $this->AddPoint($values[$key], "Serie" . $key, $serieName);
            }
        }
    }

    function AddPoint($Value, $Serie = "Serie1", $Description = "")
    {
        if (is_array($Value) && count($Value) == 1)
            $Value = $Value[0];

        $ID = 0;
        for ($i = 0; $i <= count($this->Data); $i ++) {
            if (isset($this->Data[$i]) && isset($this->Data[$i][$Serie])) {
                $ID = $i + 1;
            }
        }

        if (count($Value) == 1) {
            $this->Data[$ID][$Serie] = $Value;
            if ($Description != "")
                $this->Data[$ID]["Name"] = $Description;
            elseif (! isset($this->Data[$ID]["Name"]))
                $this->Data[$ID]["Name"] = $ID;
        } else {
            foreach ($Value as $key => $Val) {
                $this->Data[$ID][$Serie] = $Val;
                if (! isset($this->Data[$ID]["Name"]))
                    $this->Data[$ID]["Name"] = $ID;
                $ID ++;
            }
        }
    }

    /**
     * 获取表格模式的数据，用于储存表格
     *
     * @return array 一维数据，坐标对应相应的数据
     */
    public function getTableData()
    {
        $data = $this->GetData();
        $dataDesc = $this->GetDataDescription();
        $divisions = count($dataDesc['Values']);

        $result = array();
        if (is_array($dataDesc['Values']) && ! empty($dataDesc['Values'])) {
            foreach ($data as $line) {
                $lineName = $line[$dataDesc['Position']];
                $tableYValuePostion = $divisions;

                /**
                 * 如果数据中出现 0 那么认定该数据为结束标记
                 */
                foreach ($dataDesc['Values'] as $serie) {
                    $rowName = Zeed_PChart::$tableYNAMES[$tableYValuePostion];
                    $tableYValuePostion --;

                    $key = $lineName . $rowName;

                    if (strlen($line[$serie]) == 1 && $line[$serie] == 0) {
                        break;
                    }

                    $result[$key] = $line[$serie];
                }
            }
        }

        return $result;
    }

}

// End ^ Native EOL ^ encoding
