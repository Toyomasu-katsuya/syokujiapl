<?php
// ライブラリ読み込み
require "PhpSpreadsheet/vendor/autoload.php";
 
// PHPExcelインスタンス
$objPHPExcel = new PHPExcel();
 
// シートの場所（0始まり）
$objPHPExcel->setActiveSheetIndex(0);
 
// シートを取得
$activeSheet = $objPHPExcel->getActiveSheet();
 
// 文字列の記述
$activeSheet->setCellValue('A1', 12345)
->setCellValue('A2', 'テスト');
 
// Excel作成
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 
// サーバーに出力
$filename = "sample_" . date('YmdHis') . ".xlsx"; //ファイル名
$objWriter->save("data/" . $filename);
?>