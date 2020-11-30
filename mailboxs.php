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
        <title>メッセージボックス</title>
        <style type="text/css">
            body{
                width:500px;
                height:100%;
                float: none;
                margin:10px;
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
            #container {
              display: grid;
              grid-template: 30px 80% 80px 1fr / 150px 350px;
              width: 100%;
              height: 100%;
            }
            #header {
                grid-row: 1 / 2;
                grid-column: 1 / 3;
                text-align: center;
            }
            #memberSelect {
                grid-row: 2 / 4;
                grid-column: 1 / 2;
                border: solid 1px black;
                padding: 5px;
                border-radius: 5px;
                overflow-y: scroll;
            }
            #msgHistory {
                grid-row: 2 / 3;
                grid-column: 2 / 3;
                border: solid 1px black;
                padding: 5px;
                border-radius: 5px;
                overflow-y: scroll;
                overflow-x: hidden;
            }
            #msgSend {
                grid-row: 3 / 4;
                grid-column: 2 / 3;
                border: solid 1px black;
                padding: 5px;
                border-radius: 5px;
            }
            .mine {
                position: absolute;
                right:10px;
                display: inline-block;
                margin: 1.5em 15px 1.5em 0;
                padding: 5px 5px;
                min-width: 120px;
                max-width: 100%;
                color: #555;
                font-size: 16px;
                background: #e0edff;
            }

            .mine:before {
                content: "";
                position: absolute;
                top: 50%;
                left: 100%;
                margin-top: -15px;
                border: 15px solid transparent;
                border-left: 15px solid #e0edff;
            }
            .mine p {
                margin: 0;
                padding: 0;
            }
            .other {
                position: absolute;
                left:0px;
                display: inline-block;
                margin: 1.5em 0 1.5em 15px;
                padding: 5px 5px;
                min-width: 120px;
                max-width: 100%;
                color: #555;
                font-size: 16px;
                background: #e0edff;
            }

            .other:before {
                content: "";
                position: absolute;
                top: 50%;
                left: -30px;
                margin-top: -15px;
                border: 15px solid transparent;
                border-right: 15px solid #e0edff;
            }
            .other p {
                margin: 0;
                padding: 0;
            }

            .line {
                width: 100%;
                height: 30px;
                margin: 5px 0 5px 15px;
                position: relative;
            }
            #chatmsg {
                
            }
            #chatSend {
                position: absolute;
                height: 50px;
                margin: 10px;
            }
            .member {
                width:100%;
                height:30px;
            }
        </style>
<script>
var selected_user="";
function sendChat() {
  var myself = "<?php echo urlencode($_SESSION['NAME']); ?>";
  var chat = document.getElementById("chatmsg");
  var ajax = new XMLHttpRequest();
  ajax.open('post', 'chatajax.php');
  ajax.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
  ajax.responseType = "json";
  var sendMsg = "from="+myself+"&user="+selected_user+"&msg="+chat.value;
  ajax.send(sendMsg); // 通信させます。
  chat.value="";
}
function recvAJAX() {
  var myself = "<?php echo urlencode($_SESSION['NAME']); ?>";
  var ajax = new XMLHttpRequest();
  ajax.open("get", "chatajax.php?from="+myself+"&user="+selected_user);
  ajax.responseType = "json";
  ajax.send(); // 通信させます。
  ajax.addEventListener("load", function(){ // loadイベントを登録します。
    var msg = document.getElementById("msgHistory");
    var json = this.response;
    if (json) {
        var me = '<?php echo $_SESSION['NAME'] ?>';
        var html = "";
        for(var i = 0; i < json.length; i++) {
          if (json[i].from == me) {
            html += "<div class='line'><p class='mine'>" + json[i].msg + "</p></div>";
          } else {
            html += "<div class='line'><p class='other'>" + json[i].msg + "</p></div>";
          }
        }
        msg.innerHTML = html;
    }
  }, false);
}
function userSelect(username) {
  var myself = "<?php echo $_SESSION['NAME']; ?>";
  selected_user = username;
  var title = document.getElementById("header");
  title.innerHTML = myself+" <==> "+selected_user;
}
</script>
        
    </head>
    <body>
    <div id="container">
        <div id="header"></div>
        <div id="memberSelect">
            <?php
$link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
//$query = "SELECT * from members where team = '".$team."'";
$query = "SELECT * from members where team = '".$_SESSION['TEAM'] ."'"; // 取りあえずチーム内の人を表示(管理士なども追加が必要)
if ($result = mysqli_query($link, $query)) {
        $msg = array();
        foreach($result as $row) {
            if ($row['name'] == $_SESSION['NAME']) continue;
            echo "<button class='member' onclick='userSelect(\"".$row['name']."\")'>".$row['name']."</button>";
        }
}
mysqli_close($link);
            ?>
        </div>
        <div id="msgHistory"></div>
        <div id="msgSend">
            <textarea name="chatmsg" id="chatmsg" rows=5 cols=37></textarea>
            <button id="chatSend" onClick="sendChat();">送る</button>
        </div>
    </div>
<script>
setInterval(recvAJAX, 200);
//var msg = document.getElementById("msgHistory");
var container = document.getElementById("container");
container.style.height = window.innerHeight * 0.8 + "px";
</script>
    </body>
</html>