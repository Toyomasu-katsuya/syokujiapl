<?php
  @session_start();
  $ID=$_SESSION['ID'];
  $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
  // 接続状況をチェックします
  if (mysqli_connect_errno()) {
      die("データベースに接続できません:" . mysqli_connect_error() . "\n");
  }
      
  require_once "PhpSpreadsheet/vendor/autoload.php";
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Chart\Chart;
  use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
  use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
  use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
  use PhpOffice\PhpSpreadsheet\Chart\Title;

    if(@$_POST["submit"] == "ファイル出力"){
        
        $reader = new XlsxReader();
        //$reader = IOFactory::createReader('Xlsx');
        //$reader->setIncludeCharts(TRUE);
        $spreadsheet = $reader->load('template.xlsx');
        $SQL="SELECT today as '日付',breakfast_menu as '朝食メニュー',lunch_menu as '昼食メニュー',dinner_menu as '夕食メニュー' from syokuji where ID = '$ID';" ;
        echo $SQL;
        $f=0;
        $c=0;
        if ($result = mysqli_query($link, $SQL)) {
            $f=3;
            $sheet = $spreadsheet->getSheetByName('syokuji');
            foreach($result as $row){
                $sheet->setCellValue("B".$f, $row['日付']);
                $sheet->setCellValue("C".$f, $row['朝食メニュー']);
                $sheet->setCellValue("D".$f, $row['昼食メニュー']);
                $sheet->setCellValue("E".$f, $row['夕食メニュー']);
                $f++;
                
            }
        }
        $sheet = $spreadsheet->getSheetByName('Weight');
        $SQL="SELECT today as '日付',boby_weight as '体重',body_fat_percentage as '体脂肪率' from weight where ID = '$ID';" ;
        $f=0;
        $c=1;
        if ($result = mysqli_query($link, $SQL)) {
            $f=3;
            foreach($result as $row){
                $sheet->setCellValue("B".$f, $row['日付']);
                $sheet->setCellValue("C".$f, $row['体重']);
                $sheet->setCellValue("D".$f, $row['体脂肪率']);
                $f++;
                
            }
        }
        

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        //$writer = new XlsxWriter($spreadsheet);
        $today=date('Y-m-d');
        $fileName="kiroku_".$ID."_". $today.".xlsx";
        $writer->setIncludeCharts(TRUE);
        $writer->save("'.$fileName.'");
        

        // ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        // header('Cache-Control: max-age=1');
        // header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        // header('Cache-Control: cache, must-revalidate');
        // header('Pragma: public');
         
        // バッファをクリア
        ob_end_clean();

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(TRUE);
        $writer->save('php://output');
        exit;
        
    }
            /*$SQL="select today as '日付' ,breakfast_menu as '朝食メニュー', lunch_menu as '昼食メニュー',dinner_menu as '夕食メニュー' from syokuji where ID = '$ID' ;";
            //sql実行
            if ($result = mysqli_query($link, $SQL)){
                if(mysqli_num_rows($result) > 0 ) { //データが存在していれば
                foreach($result as $row){
                    $key = array_keys($row);
                    
                }
        }*/
        
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Mypage -記録閲覧-</title>
        <style type="text/css">
            body{
                width:500px; 
                float: none;
                text-align:center;
                margin:80px;
                margin-left: 30%;   
                background-color:#ccffff;
            }
            .btn-square {
                display: inline-block;
                width:150px;
                padding: 0.5em 1em;
                text-decoration: none;
                background: #668ad8;/*ボタン色*/
                color: #FFF;
                border-bottom: solid 4px #627295;
                border-radius: 3px;
                
            }
            .btn-square:active {
                /*ボタンを押したとき*/
                -webkit-transform: translateY(4px);
                transform: translateY(4px);/*下に動く*/
                border-bottom: none;/*線を消す*/
                
            }
        .tab_wrap{width:400px; margin:40px; float: left;}
        input[type="radio"]{display:none;}
        .tab_area{font-size:0; margin:0 10px; }
        .tab_area label{width:100px; margin:0 5px; display:inline-block; padding:12px 0; color:#999; background:#ddd; text-align:center; font-size:13px; 
        cursor:pointer; transition:ease 0.2s opacity;font-weight:bold;}
        .tab_area label:hover{opacity:0.5;}
        .panel_area{background:white;}
        .tab_panel{width:100%; padding:30px 0; display:none;  }
        .tab_panel p{font-size:14px; letter-spacing:1px; text-align:center;font-weight:bold; }
        
        #tab1:checked ~ .tab_area .tab1_label{background:#33ffff; color:#000;border-style:solid}
        #tab1:checked ~ .panel_area #panel1{display:block;border-style:solid}
        #tab2:checked ~ .tab_area .tab2_label{background:#33ffff; color:#000;border-style:solid}
        #tab2:checked ~ .panel_area #panel2{display:block;border-style:solid}
        #tab4:checked ~ .tab_area .tab4_label{background:#33ffff; color:#000;border-style:solid}
        #tab4:checked ~ .panel_area #panel4{display:block;border-style:solid}
            
        </style>
        
    </head>
    <body>
        <a href="main.php" class="btn-square">メインへ</a>
        <form action="kiroku.php" method="post">
            <fieldset>
                <legend><B>メニュー</B></legend>
                <!--入力欄-->
                <div class="tab_wrap">
             <input id="tab1" type="radio" name="tab_btn">
             <input id="tab2" type="radio" name="tab_btn">
             <input id="tab4" type="radio" name="tab_btn"checked>
             
             <div class="tab_area">
                 <label class="tab4_label" for="tab4">食事記録</label>
                 <label class="tab2_label" for="tab2">食事画像</label>
                 <label class="tab1_label" for="tab1">体重・体脂肪率記録</label>
            </div>
            <div class="panel_area">
                <form action="kiroku.php" method="post">
                <div id="panel4" class="tab_panel">
                    <?php
                    $SQL="select today as '日付' ,breakfast_menu as '朝食メニュー', lunch_menu as '昼食メニュー',dinner_menu as '夕食メニュー' from syokuji where ID = '$ID' ;";
                    //sql実行
                    if ($result = mysqli_query($link, $SQL)){
                        if(mysqli_num_rows($result) > 0 ) { //データが存在していれば
                        foreach($result as $row){
                            $key = array_keys($row);
                            
                        }
                        ?>

                    <table border="0">
                        <tr>
                        <?php
                        for ($f = 0; $f < count($key); $f++){
                            echo "<th>".$key[$f]."</th>";
                            
                        }
                        ?>
                        </tr>
                        <?php
                        foreach($result as $row){
                        ?>
                        <tr>
                        <?php
                        for($j = 0; $j< count($row); $j++ ){
                        ?>
                        <td><?php echo $row[$key[$j]]?></td>
                        <?php } ?>
                        </tr>
                        <?php }
                        ?>
                        </table>
                        <?php
                        }else{
                         echo "記録がありません";
                         }
                         }?>
                </div>
                </from>
                <form action="kiroku.php" method="post">
                <div id="panel2" class="tab_panel">
                    <?php
                    $SQL="select today as '日付' ,breakfast_file as '朝食メニュー', lunch_file as '昼食メニュー',dinner_file as '夕食メニュー' from syokuji where ID = '$ID' ;";
                    //sql実行
                    if ($result = mysqli_query($link, $SQL)){
                        if(mysqli_num_rows($result) > 0 ) { //データが存在していれば
                        foreach($result as $row){
                            $key = array_keys($row);
                            
                        }
                        ?>

                    <table border="0">
                        <tr>
                        <?php
                        for ($f = 0; $f < count($key); $f++){
                            echo "<th>".$key[$f]."</th>";
                            
                        }
                        ?>
                        </tr>
                        <?php
                        foreach($result as $row){
                        ?>
                        <tr>
                        <?php
                        for($j = 0; $j< count($row); $j++ ){
                        ?>
                        <td><?php if(strstr("日付",$key[$j]) === false){
                            if($row[$key[$j]]!=""){
                                echo "<th> <img src='https://toyomasu.naviiiva.work/original_application/".$row[$key[$j]]."'width='15' height='15'></th>";
                            }
                            }else{
                            echo $row[$key[$j]];}?></td>
                        <?php } ?>
                        </tr>
                        <?php }
                        ?>
                        </table>
                        <?php
                        }else{
                         echo "記録がありません";
                         }
                         }?>
                </div>
                </from>
                <?php
                    $SQL="select today,boby_weight,body_fat_percentage from weight where ID = '$ID' ;";
                    $day="";
                    $boby_weight="";
                    $body_fat_percentage="";
                    //sql実行
                    if ($result = mysqli_query($link, $SQL)){
                        if(mysqli_num_rows($result) > 0 ) { //データが存在していれば
                        foreach($result as $row){
                            $day= $day . '"'. $row['today'].'",';
                            $boby_weight= $boby_weight . '"'. $row['boby_weight'].'",';
                            $body_fat_percentage= $body_fat_percentage . '"'. $row['body_fat_percentage'].'",';
                            
                        }
                        $day=trim($day,",");
                        $boby_weight=trim($boby_weight,",");
                        $body_fat_percentage=trim($body_fat_percentage,",");
                        }
                    }
                
                ?>

                <form action="kiroku.php" method="post">
                <div id="panel1" class="tab_panel">
                    <canvas id="canvas"></canvas>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
                <script>
                window.onload = function() {
                    ctx = document.getElementById("canvas").getContext("2d");
                    window.myBar = new Chart(ctx, {
                        type: 'bar', // ここは bar にする必要があります
                        data: barChartData,
                        options: complexChartOption
                        
                    });
                    
                };
                </script>
                <script>
                // とある4週間分のデータログ
                var barChartData = {
                    labels: [<?php echo $day; ?>
                    ],
                    datasets: [
                        {
                            type: 'line',
                            label: 'boby_weight',
                            data: [<?php echo $boby_weight;?> 
                            ],
                            borderColor : "rgba(254,97,132,0.8)",
                            fill : false,
                            yAxisID: "y-axis-1",
                            
                        },
                        {
                            type: 'line',
                            label: 'body_fat_percentage',
                            data: [<?php echo $body_fat_percentage ?>
                            ],
                            borderColor : "rgba(54,164,235,0.8)",
                            fill : false,
                            yAxisID: "y-axis-2",
                            
                        },
                        ],
                    
                };
                </script>
                <script>
                var complexChartOption = {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            id: "y-axis-1",   // Y軸のID
                            type: "linear",   // linear固定 
                            position: "left", // どちら側に表示される軸か？
                            ticks: {          // スケール
                            max: 100,
                            min: 0,
                            stepSize: 20
                                
                            },
                            
                        }, {
                            id: "y-axis-2",
                            type: "linear", 
                            position: "right",
                            ticks: {
                                max: 30,
                                min: 5,
                                stepSize: 5
                                
                            },
                            
                        }],
                        
                    }
                    
                };
                </script>
                </div>
            </div>
            
            </fieldset>
            <p><input type="submit" name="submit" value="ファイル出力"></p>
        </form>
        <a href=SignUp.php> 個人設定 </a>
        <a href="logout.php" >ログアウト</a>


    </body>
</html>