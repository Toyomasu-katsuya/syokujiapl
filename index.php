<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <style type="text/css">
            body{
                width:500px; 
                float: none;
                text-align:center;
                margin:80px;
                margin-left: 30%;   
                background-color:#ccffff;
            }
            
        </style>
    </head>
    <body>
        <h1>ようこそ、〇〇のページです</h1>
        <form action="index.php" method="post">
            <fieldset>
                <legend><B>ログイン</B></legend>
                <!--入力欄-->
                <B>ID:</B><input type="text" name="ID" placeholder="メールアドレス"><br>
                <B>PW:</B><input type="password" name="PW"><br>
                <!--送信ボタンを用意する-->
                <input type="submit" name="submit" value="ログイン"><br>
                <a href='reSignUp.php'>パスワードを忘れた場合はこちら</a>
            </fieldset>
            <fieldset>
                <legend><B>お知らせ枠</B></legend>
                <input type="text" name="news" value="管理者はtest@test.com,PW=admin"><br>
                <input type="text" name="news" value="一般はtest_member@test.com,PW=test"><br>
                <!--送信ボタンを用意する-->
                
            </fieldset>
        </form>
        <a href=member_signup.php> 新規代表者登録 </a>

        <?php
        $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
        // 接続状況をチェックします
        if (mysqli_connect_errno()) {
            die("データベースに接続できません:" . mysqli_connect_error() . "\n");
            
        }
        
        $ID=@$_POST["ID"];
        $PW=@$_POST["PW"];
        $SQL='select * from members where userid = "'.$ID.'" ;';
        if ($result = mysqli_query($link, $SQL)) {
            foreach ($result as $row) {
                if($ID==$row['userid']){
                    //if(password_verify($PW, $row['PW'])){
                    if($PW==$row['password']){
                        @session_start();
                        $_SESSION['ID'] = $ID;
                        $_SESSION['NAME'] = $row['name'];
                        $_SESSION['TEAM'] = $row['team'];
                        if($row['Authority']=='管理者'){
                            header("Location: main_kanri.php");
                            exit;
                        }else{
                            header("Location: main.php");
                            exit;
                        }
                    } else{
                    echo "PWがあっていません";
                }
            }else{
                echo "IDが見つかりません";
            }
        }
        }
        
        ?>
        
        
   
    </body>
</html>