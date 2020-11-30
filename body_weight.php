<?php
 @session_start();
 $ID=$_SESSION['ID'];
 date_default_timezone_set ('Asia/Tokyo');
 $today=date('Y-m-d');
 $boby_weight="";
 $body_fat_percentage="";
 $message="";
 $weekday=date('Y-m-d',strtotime("-7 day"));
 
 $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
 // 接続状況をチェックします
 if (mysqli_connect_errno()) {
     die("データベース接続に失敗しました:" . mysqli_connect_error() . "\n");
 }
 //デフォルト検索
 $SQL='select * from weight where ID = "'.$ID.'" AND today = "'.$today.'"; ';
 if ($result = mysqli_query($link, $SQL)) {
            foreach ($result as $row) {
                if(!empty($row['boby_weight'])){
                    $boby_weight=$row['boby_weight'];
                }
                if(!empty($row['body_fat_percentage'])){
                    $body_fat_percentage=$row['body_fat_percentage'];
                }
                if(!empty($row['message'])){
                    $message=$row['message'];
                }
                
            }
     
 }
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Mypage-体重・体脂肪率登録-</title>
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
            
        </style>
    </head>
    <body>
        <form action="body_weight.php" method="post" enctype="multipart/form-data">
            <a href="main.php" class="btn-square">メインへ</a>
            <fieldset>
                <legend><B>体重・体脂肪率登録</B></legend>
                <input type="date" name="today" value="<?php echo $today; ?>"><br>
                <!--入力欄-->
                <fieldset>
                <legend><B>記録</B></legend>
                体重:<input type="text" name="boby_weight" value="<?php echo $boby_weight; ?>" >kg <br>
                体脂肪率:<input type="text" name="body_fat_percentage" value="<?php echo $body_fat_percentage; ?>">% <br>
                一言メッセージ<input type="text">
                </fieldset>
                <?php
                    $SQL="select today,boby_weight,body_fat_percentage from weight where ID = '$ID' AND  today >= '$weekday';";
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
                <p><input type="submit" name="submit" value="登録"></p>
                
            </fieldset>
        </form>
        <a href="logout.php" >ログアウト</a>

        <?php
        if(@$_POST["submit"] == "登録"){
            $today=@$_POST["today"];
            $boby_weight=@$_POST["boby_weight"];
            $body_fat_percentage=@$_POST["body_fat_percentage"];
            $message=@$_POST["message"];
            $SQL='insert into weight (ID ,today ,boby_weight ,body_fat_percentage ,message) 
            values("'.$ID.'" , "'.$today.'" , "'.$boby_weight.'","'.$body_fat_percentage.'" , "'.$message.'");';
            
            if ($result = mysqli_query($link, $SQL)) {
                if(is_bool($result)){
                    if(strpos($SQL,'insert') !== false){
                        echo "<B>登録が完了しました</B>";
                    }
                    
                }else{
                    echo "登録に失敗しました";
                    
                }
                
            }else{
                echo "登録に失敗しました";
                
            }
        }
        
        ?>
        
        
   
    </body>
</html>