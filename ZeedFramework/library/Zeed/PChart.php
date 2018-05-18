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
 * @package    Zeed_Image
 * @subpackage PChart
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id$
 */

require_once ZEED_PATH_3rd . 'pChart/pChart.php';

class Zeed_PChart extends pChart
{
    public static $tableYNAMES = array('','A','B','C','D','E','F','G','H','I','J','K','L');
    protected $_image = null;

    protected $_dataFontName = null;
    protected $_dataFontSize = null;

    public function setDataFontProperties($fontName, $fontSize)
    {
        $this->_dataFontName = $fontName;
        $this->_dataFontSize = $fontSize;
    }

    /**
     * 获取一个表格的随机单元
     *
     * @param string $size 8x9 标识 X 为8 Y 为9的表格
     * @param integer $num 获取单元个数
     * @return array|null
     */
    public static function getTableRandomUnit($size, $num)
    {
        $size = trim(strtolower($size));
        $units = null;

        if ( preg_match('#^([0-9]+)x([0-9]+)$#i', $size, $matchs)) {
            $xSize = $matchs[1];
            $ySize = $matchs[2];

            $units = array();
            $i = 0;

            do {
                $x = range(1, $xSize);
                $y = array_slice(self::$tableYNAMES, 1, $ySize);

                $xIndex = array_rand($x, 1);
                $yIndex = array_rand($y, 1);

                $unit = $y[$yIndex].$x[$xIndex];

                if (in_array($unit, $units)) {
                    continue;
                }

                $units[] = $unit;
                $i++;
            }
            while ($i < $num);
//            for ($i = 0; $i < $num; $i++) {
//                $x = range(1, $xSize);
//                $y = array_slice(self::$tableYNAMES, 1, $ySize);
//
//                $xIndex = array_rand($x, 1);
//                $yIndex = array_rand($y, 1);
//
//                $unit = $y[$yIndex].$x[$xIndex];
//                if ()
//                $units[] = $y[$yIndex].$x[$xIndex];
//            }
        }

        return $units;
    }


    /* Compute and draw the scale */
    /**
     *
     * 表表格模式下最后一列以及最后一行将被忽略
     *
     * @param $Data
     * @param $DataDescription
     * @param $ScaleMode
     * @param $R
     * @param $G
     * @param $B
     * @param $DrawTicks
     * @param $Angle 画 X Y 轴名称时的位置角度
     * @param $Decimals
     * @param $WithMargin
     * @param $SkipLabels
     * @param boolean $RightScale 是否在右边标记 Y 轴，如果是 Y 轴的标记将出现在右边
     */
    function drawTable($Data, $DataDescription, $ScaleMode, $R, $G, $B, $DrawTicks = TRUE, $Angle = 0, $Decimals = 1, $WithMargin = FALSE, $SkipLabels = 1, $RightScale = FALSE)
    {
        /**
         * 画个表格，虽然是表格但是 X 轴的数据库目前还是由  DATA 传递的
         *
         * @var unknown_type
         */
        $table = true;
        $RightScale = ($RightScale && ! $table) ? true : false;

        /* Validate the Data and DataDescription array */
        $this->validateData("drawScale", $Data);

        $C_TextColor = $this->AllocateColor($this->Picture, $R, $G, $B);

        $this->drawLine($this->GArea_X1, $this->GArea_Y1, $this->GArea_X1, $this->GArea_Y2, $R, $G, $B);
        $this->drawLine($this->GArea_X1, $this->GArea_Y2, $this->GArea_X2, $this->GArea_Y2, $R, $G, $B);

            $Divisions = $this->Divisions;

        /**
         * 如果是表格模式，那么直接统计 Values 个数，也就是 $DataDescription['Values'] 数据个数
         */
        if ($table) {
            $Divisions = count($DataDescription['Values']);
        }

        $this->DivisionCount = $Divisions;

        $DataRange = $this->VMax - $this->VMin;
        if ($DataRange == 0) {
            $DataRange = .1;
        }

        $this->DivisionHeight = ($this->GArea_Y2 - $this->GArea_Y1) / $Divisions;
        $this->DivisionRatio = ($this->GArea_Y2 - $this->GArea_Y1) / $DataRange;

        $this->GAreaXOffset = 0;
        if (count($Data) > 1) {
            if ($WithMargin == FALSE)
                $this->DivisionWidth = ($this->GArea_X2 - $this->GArea_X1) / (count($Data) - 1);
            else {
                $this->DivisionWidth = ($this->GArea_X2 - $this->GArea_X1) / (count($Data));
                $this->GAreaXOffset = $this->DivisionWidth / 2;
            }
        } else {
            $this->DivisionWidth = $this->GArea_X2 - $this->GArea_X1;
            $this->GAreaXOffset = $this->DivisionWidth / 2;
        }

        $this->DataCount = count($Data);

        if ($DrawTicks == FALSE)
            return (0);

        if ($table) {
            $YPos = $this->GArea_Y2 - ($this->DivisionHeight / 2);
        } else {
            $YPos = $this->GArea_Y2;
        }

        $XMin = NULL;
        /**
         * 画 Y 轴标记
         * Y 轴在不是表格模式的时候，应该是计算出来的，因为是划线
         */
        $rowNum = $Divisions; /* 当前表格行数 */

        /**
         * 用于表格 Y 轴坐标
         * pChart 画 Y 轴时从下方开始画，为了兼容这种模式，设置第一个为空，以便表格和标记对应
         * @todo 当前表格只能有 12 行，如要更多，在 $tableYValues 中加入 Y 轴描述
         */
        $tableYValuePostion = $rowNum;

        for ($i = 1; $i <= $Divisions + 1; $i ++) {
            $Value = self::$tableYNAMES[$tableYValuePostion];
            $tableYValuePostion --;


            $Position = imageftbbox($this->FontSize, 0, $this->FontName, $Value);
            $TextWidth = $Position[2] - $Position[0];

            if ($RightScale) {
                imagettftext($this->Picture, $this->FontSize, 0, $this->GArea_X2 + 10, $YPos + ($this->FontSize / 2), $C_TextColor, $this->FontName, $Value);
                if ($XMin < $this->GArea_X2 + 15 + $TextWidth || $XMin == NULL) {
                    $XMin = $this->GArea_X2 + 15 + $TextWidth;
                }
            } else {
                imagettftext($this->Picture, $this->FontSize, 0, $this->GArea_X1 - 15, $YPos + ($this->FontSize / 2), $C_TextColor, $this->FontName, $Value);
                if ($XMin > $this->GArea_X1 - 10 - $TextWidth || $XMin == NULL) {
                    $XMin = $this->GArea_X1 - 10 - $TextWidth;
                }
            }

            $YPos = $YPos - $this->DivisionHeight;
        }

        /* Write the Y Axis caption if set */
        /**
         * 画一个关于 Y 轴的描述
         */
        if (isset($DataDescription["Axis"]["Y"])) {
            $Position = imageftbbox($this->FontSize, 90, $this->FontName, $DataDescription["Axis"]["Y"]);
            $TextHeight = abs($Position[1]) + abs($Position[3]);
            $TextTop = (($this->GArea_Y2 - $this->GArea_Y1) / 2) + $this->GArea_Y1 + ($TextHeight / 2);

            if ($RightScale)
                imagettftext($this->Picture, $this->FontSize, 90, $XMin + $this->FontSize, $TextTop, $C_TextColor, $this->FontName, $DataDescription["Axis"]["Y"]);
            else
                imagettftext($this->Picture, $this->FontSize, 90, $XMin - $this->FontSize, $TextTop, $C_TextColor, $this->FontName, $DataDescription["Axis"]["Y"]);
        }

        /* Horizontal Axis */
        /**
         * 定义 X 轴起始坐标
         * GAreaXOffset X 轴坐标偏移值， 一般为 0
         */
        $XPos = $this->GArea_X1 + $this->GAreaXOffset;

        /**
         * 让 X 轴的标记定位在表格的中
         */
        if ($table) {
            $XPos = $XPos + ((($XPos + $this->DivisionWidth) - $XPos) / 2);
        }

        $ID = 1;
        $YMax = NULL;

        /**
         * 画 X 轴标记
         */
        foreach ($Data as $Key => $Values) {

            if ($ID % $SkipLabels == 0) {
                $Value = $Data[$Key][$DataDescription["Position"]];
                if ($DataDescription["Format"]["X"] == "number")
                    $Value = $Value . $DataDescription["Unit"]["X"];
                if ($DataDescription["Format"]["X"] == "time")
                    $Value = $this->ToTime($Value);
                if ($DataDescription["Format"]["X"] == "date")
                    $Value = $this->ToDate($Value);
                if ($DataDescription["Format"]["X"] == "metric")
                    $Value = $this->ToMetric($Value);
                if ($DataDescription["Format"]["X"] == "currency")
                    $Value = $this->ToCurrency($Value);

                $Position = imageftbbox($this->FontSize, $Angle, $this->FontName, $Value);
                $TextWidth = abs($Position[2]) + abs($Position[0]);
                $TextHeight = abs($Position[1]) + abs($Position[3]);

                /**
                 * 画 X 轴数据
                 */
                if ($Angle == 0) {
                    $YPos = $this->GArea_Y2 + 18;
                    imagettftext($this->Picture, $this->FontSize, $Angle, floor($XPos) - floor($TextWidth / 2), $YPos, $C_TextColor, $this->FontName, $Value);
                } else {
                    $YPos = $this->GArea_Y2 + 10 + $TextHeight;
                    if ($Angle <= 90)
                        imagettftext($this->Picture, $this->FontSize, $Angle, floor($XPos) - $TextWidth + 5, $YPos, $C_TextColor, $this->FontName, $Value);
                    else
                        imagettftext($this->Picture, $this->FontSize, $Angle, floor($XPos) + $TextWidth + 5, $YPos, $C_TextColor, $this->FontName, $Value);
                }
                if ($YMax < $YPos || $YMax == NULL) {
                    $YMax = $YPos;
                }
            }

            $XPos = $XPos + $this->DivisionWidth;
            $ID ++;
        }

        /* Write the X Axis caption if set */
        /**
         * 画一个关于 X 轴的描述
         */
        if (isset($DataDescription["Axis"]["X"])) {
            $Position = imageftbbox($this->FontSize, 90, $this->FontName, $DataDescription["Axis"]["X"]);
            $TextWidth = abs($Position[2]) + abs($Position[0]);
            $TextLeft = (($this->GArea_X2 - $this->GArea_X1) / 2) + $this->GArea_X1 + ($TextWidth / 2);
            imagettftext($this->Picture, $this->FontSize, 0, $TextLeft, $YMax + $this->FontSize + 5, $C_TextColor, $this->FontName, $DataDescription["Axis"]["X"]);
        }

        /**
         * 如果是表格直接输出里面的数据
         * 表格的数据为了兼容 pChat 从坐标轴 X Y 的 0 0 （LEFT BOTTON）开始，先画第一列，以此类推
         */
        $dataXPos = $this->GArea_X1 + $this->GAreaXOffset;
        $dataXPos = $dataXPos + ((($dataXPos + $this->DivisionWidth) - $dataXPos) / 2);

        $fontName = $this->FontName;
        $fontSize = $this->FontSize;
        if (null !== $this->_dataFontName && null !== $this->_dataFontSize) {
            $this->setFontProperties($this->_dataFontName, $this->_dataFontSize);
        }

        foreach ($Data as $key => $value) {
            $dataYPos = $this->GArea_Y2 - ($this->DivisionHeight / 2);
            foreach ($DataDescription['Values'] as $serieKey) {
                $tokenString = strtoupper($Data[$key][$serieKey]);

                $this->drawTextBox($dataXPos, $dataYPos, $dataXPos, $dataYPos, $tokenString, 0, 0, 0, 0, ALIGN_CENTER, $Shadow = false);

                $dataYPos = $dataYPos - $this->DivisionHeight;
            }

            $dataXPos = $dataXPos + $this->DivisionWidth;
        }

        $this->setFontProperties($fontName, $fontSize);
    }

    /* Validate data contained in the data array */
    function validateData($FunctionName, &$Data)
    {
        $DataSummary = array();

        if (is_array($Data)) {
            foreach ($Data as $key => $Values) {
                foreach ($Values as $key2 => $Value) {
                    if (! isset($DataSummary[$key2]))
                        $DataSummary[$key2] = 1;
                    else
                        $DataSummary[$key2] ++;
                }
            }
        }

        if (! empty($DataSummary) && max($DataSummary) == 0)
            $this->Errors[] = "[Warning] " . $FunctionName . " - No data set.";

        foreach ($DataSummary as $key => $Value) {
            if ($Value < max($DataSummary)) {
                $this->Errors[] = "[Warning] " . $FunctionName . " - Missing data in serie " . $key . ".";
            }
        }
    }

    public function getTextWidth($text)
    {
        $position  = imageftbbox($this->FontSize,0,$this->FontName,$text);
        $width = $position[2]-$position[0];
        return $width;
    }

    public function getImage()
    {
        if (null === $this->_image) {
            ob_start();
            $this->output();
            $this->_image = ob_get_contents();
            ob_end_clean();
        }

        return $this->_image;
    }

    /**
     * 以 PNG 的方式显示图片
     */
    public function output()
    {
        if ($this->ErrorReporting) {
            $this->printErrors($this->ErrorInterface);
        }

        /* Save image map if requested */
        if ($this->BuildMap) {
            $this->SaveImageMap();
        }

        header('Content-type: image/png');

        ob_start();
        imagepng($this->Picture);
        $this->_image = ob_get_contents();
        ob_end_clean();

        echo $this->_image;
    }
}

// End ^ Native EOL ^ encoding
