<?php
 @session_start();
 $team=$_SESSION['TEAM'];
 $param_json = json_encode( $team );
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>選手登録QRコード</title>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="jquery.qrcode.min.js"></script>
        <script type="text/JavaScript">
        $(function(){
            var param = JSON.parse('<?php echo $param_json; ?>'); 
            var qrtext = "https://toyomasu.naviiiva.work/original_application/member_signup.php?pre_userid=team_member&mode=regist_form&team="+param;
            var utf8qrtext = unescape(encodeURIComponent(qrtext));
            $("#qrcode").html("");
            $("#qrcode").qrcode({text:utf8qrtext}); 
            
        });
        </script>
    </head>
    <body>
        <form action="id_url.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend><B>選手登録用QRコード</B></legend>
                <div id="qrcode"></div>
            </fieldset>
        </form>
        <a href="kanri_menu.php" class="btn-square">戻る</a>

        <?php
        $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
        // 接続状況をチェックします
        if (mysqli_connect_errno()) {
            die("データベースに接続できません:" . mysqli_connect_error() . "\n");
            
        }
        
        
        ?>
        
        
   
    </body>
</html>