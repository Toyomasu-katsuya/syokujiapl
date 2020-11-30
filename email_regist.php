<?php 
/* フォームからメールアドレスを取得 */
$email = $_POST["email"];
 
/* エラーメッセージ配列 */
$error = array();
 
/* データベースに接続 */
$link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
}
 
/* メールアドレス入力チェック */
if($email == "") { //未入力の場合、エラーを返す
  //エラー配列に値を代入  
  array_push($error, "メールアドレスを入力してください。"); //エラー配列に値を代入
} else {
 //仮ユーザーIDの生成
 $pre_user_id = uniqid(rand(100,999));
 
 //SQL文を発行
 $query = "insert into members(userid,pre_userid) values('$email','$pre_user_id')";
 $result = mysqli_query($link, $query);
 
 /* データベース登録チェック */
 if($result == false) {
   array_push($error, "データベースに登録できませんでした。"); //エラー配列に値を代入
 } else {
 
   /* 取得したメールアドレス宛にメールを送信 */
   mb_language("japanese");
   mb_internal_encoding("utf-8");
 
   $to = $email;
   $subject = "会員登録URL送信メール";
   $message = "以下のURLより会員登録してください。\n".
   "https://toyomasu.naviiiva.work/original_application/member_signup.php?pre_userid=$pre_user_id&mode=regist_form";
   $header = "From:test@test.com";
 
   if(!mb_send_mail($to, $subject, $message, $header)) {  //メール送信に失敗したら
     array_push($error,"メールが送信できませんでした。
<a href='https://toyomasu.naviiiva.work/original_application/member_signup.php?pre_userid=$pre_user_id'>遷移先</a>"); //エラー配列に値を代入    
    }
  }
}
 
/*エラーがあるかないかによって表示の振り分け($error配列の確認）*/
if(count($error) > 0) {  //エラーがあった場合
  /*エラー内容表示*/
  foreach($error as $value) {
?>
<table>
  <caption>メールアドレス登録エラー</caption>
  <tr>
    <td class="item">Error：</td>
    <td><?php print $value; ?></td>
  </tr>
</table>
<?php
  }  //foreach文の終了
} else {  //エラーがなかった場合      
?>
<table>
  <caption>メール送信成功しました。</caption>
  <tr>
    <td class="item">送信先メールアドレス：</td>
    <td><?php print $email ?></td>
  </tr>
</table>
<?php
}
?>