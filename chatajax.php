<?php
$link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $from = $_POST["from"];
        $user = $_POST["user"];
        $msg = $_POST["msg"];
        $insert = "insert into msg_chat(from_user,user,msg) values(\"$from\",\"$user\",\"$msg\")";
        if ($result = mysqli_query($link, $insert)) {
//              header("Location: chat.php");
        }
} else {
        $from = $_GET["from"];
        $user = $_GET["user"];
        $query = "SELECT * from msg_chat where (from_user = '".$from."' and user = '".$user."') or (from_user = '".$user."' and user = '".$from."') order by id desc";
        if ($result = mysqli_query($link, $query)) {
                $msg = array();
                foreach($result as $row) {
                        $msg[] = array(
                                'from'=>$row['from_user'],
                                'user'=>$row['user'],
                                'msg'=>$row['msg']
                        );
                }
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($msg);
        }
}
mysqli_close($link);
?>