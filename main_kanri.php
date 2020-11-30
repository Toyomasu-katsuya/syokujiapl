<?php
@session_start();
    // ログインしていなければ login.php に遷移
    if (!isset($_SESSION['ID'])) {
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理画面</title>
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
<script>
function sendChat() {
  var user = <?php echo $_SESSION['NAME']; ?>;
  var chat = document.getElementById("chatmsg");
  var ajax = new XMLHttpRequest();
  ajax.open('post', 'chatajax.php');
  ajax.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
  ajax.responseType = "json";
  var sendMsg = &user="+user+"&msg="+chat.value;
  ajax.send(sendMsg); // 通信させます。
  chat.value="";
}
function recvAJAX() {
  var ajax = new XMLHttpRequest();
  ajax.open("get", "chatajax.php?room=<?php echo $room ?>");
  ajax.responseType = "json";
  ajax.send(); // 通信させます。
  ajax.addEventListener("load", function(){ // loadイベントを登録します。
    var msg = document.getElementById("msgArea");
    var json = this.response;
    var html = "";
    for(var i = 0; i < json.length; i++) {
      if (json[i].user == <?php echo $_SESSION['id'] ?>) {
        html += "<div class='line'><p class='mine'>" + json[i].msg + "</p></div>";
      } else {
        html += "<div class='line'><p class='other'>" + json[i].msg + "</p></div>";
      }
    }
    msg.innerHTML = html;
  }, false);
}
</script>
    </head>
    <body>
        <h3>ようこそ、<?php echo $_SESSION['NAME'];?>さん</h3>
        <h3>こちらは管理者専用ページです</h3>
        <script type="text/javascript">
            now=new Date();
            document.write("今日は",now.getMonth() +1,"月",now.getDate(),"日","です");
        </script>
        <form action="welcome.php" method="post">
            <fieldset>
                <legend><B>メニュー</B></legend>
                <!--入力欄-->
                <a href="member_list.php" class="btn-square">メンバー一覧表、記録閲覧表</a>
                <a href="kanri_menu.php" class="btn-square">メンバー管理画面</a><br>
                <a href="mailboxs.php" class="btn-square">メッセージボックス</a>
                <a href="#" class="btn-square">設定</a>
            </fieldset>
        </form>
        <a href=SignUp.php> 個人設定 </a>
        <a href="logout.php" >ログアウト</a>

        <?php
        $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
        // 接続状況をチェックします
        if (mysqli_connect_errno()) {
            die("データベースに接続できません:" . mysqli_connect_error() . "\n");
            
        }
        
        $ID=@$_POST["ID"];
        $PW=@$_POST["PW"];
        $SQL='select * from id_application where ID = "'.$ID.'" ;';
        if ($result = mysqli_query($link, $SQL)) {
            foreach ($result as $row) {
                if($ID==$row['ID']){
                    if(password_verify($PW, $row['PW'])){
                        @session_start();
                        $_SESSION['ID'] = $ID;
                        header("Location: main.php");
                        exit;
                        
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