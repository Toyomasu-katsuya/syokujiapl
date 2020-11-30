<?php
/* 入力フォームからパラメータを取得 */
$formList = array('mode','pre_userid', 'input_email', 'input_password', 'input_name','input_team');
 
/* ポストデータを取得しパラメータと同名の変数に格納 */
foreach($formList as $value) {
  $$value = $_POST[$value];
}
 
/* エラーメッセージの初期化 */
$error = array();
 
/* データベース接続設定 */
$link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
}

 
/* ユーザーIDチェック */
$query = "select userid from members where userid = '$input_email' and team = '$input_team'"; 
$resultId = mysqli_query($link, $query);
     
if(mysqli_num_rows($resultId) > 0 ) { //ユーザーIDが存在
  array_push($error,"このユーザーIDはすでにこのチームには登録されています。");
}

$query = "select userid from members where pre_userid = '$pre_userid'"; 
$resultId = mysqli_query($link, $query);
 
$Authority=0;

if(mysqli_num_rows($resultId) > 0 ) { //仮ユーザーIDが存在
  $Authority=1;//管理者として登録
} 
    
if(count($error) == 0) {
   
  //登録するデーターにエラーがない場合、memberテーブルにデータを追加する。
  if($Authority==1){
    $query = "update members set userid='$input_email', password='$input_password', name='$input_name', team='$input_team', Authority='管理者',pre_userid='' where pre_userid = '$pre_userid'" ;
    $result = mysqli_query($link, $query);

  }else{
    $query = "insert into members(userid, password, name, team,  Authority) values('$input_email','$input_password','$input_name','$input_team','一般')" ;
    $result = mysqli_query($link, $query);
  }
  if($result){  //登録完了  

    /* 登録完了メールを送信 */
    mb_language("japanese");  //言語の設定
    mb_internal_encoding("utf-8");//内部エンコーディングの設定
   
    $to = $input_email;
    $subject = "会員登録URL送信メール";
    $message = "会員登録ありがとうございました。\n"."登録いただいたユーザーIDは[$input_email]です。";
    $header = "From:test@test.com";
   
    if(!mb_send_mail($to, $subject, $message, $header)) {  //メール送信に失敗したら
      array_push($error,"メールが送信できませんでした。<br>ただしデータベースへの登録は完了しています。");
    }
  } else {  //データベースへの登録作業失敗
    array_push($error, "データベースに登録できませんでした。");
  }
}
if(count($error) == 0) {    
?>
<table>
  <caption>データベース登録完了</caption>
  <tr>
    <td class="item">Thanks：</td>
    <td>登録ありがとうございます。<br>登録完了のお知らせをメールで送信しましたので、ご確認ください。</td>
  </tr>
</table>
<?php
/* エラー内容表示 */
} else {
?>
<table>
  <caption>データベース登録エラー</caption>
  <tr>
  <td class="item">Error：</td>
  <td>
  <?php
  foreach($error as $value) {
    print $value;
  ?>
  </td>
  </tr>
</table>
<?php
  }
}
?>