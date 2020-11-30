<?php
/*pre_useridの値を取得*/
if($mode == "regist_form") {
  $pre_userid = $_GET['pre_userid'];
}

$pre_useridflg=0;

if($pre_userid=="team_member"){
  $pre_useridflg=1;
  $team = $_GET['team'];
}

/* pre_userid 有効チェック */
$errorFlag = true;
 
/* データベース接続設定 */
$link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
    }

 
/* 取得したユニークIDをキーに登録されたメールアドレスを取得 */

$query = "select userid from members where pre_userid = '$pre_userid'";
$result = mysqli_query($link, $query);
 
/*データベースより取得したメールアドレスを表示*/
if(mysqli_num_rows($result) > 0){ //取得した結果のデータの数が0以上なら → データが取得できた
  //データが正常に取得できた
  $errorFlag = false;
  $data = mysqli_fetch_array($result); 
  $email = $data['userid'];
}

 
if($errorFlag && $pre_useridflg!=1) {  // pre_useridが無効
?>
<table>
  <caption>メールアドレス登録エラー</caption>
  <tr>
    <td class="item">Error：</td>
    <td>このURLは利用できません。<br>もう一度メールアドレスの登録からお願いします。<br> <a href="member_signup.php">会員登録ページ</a></td>
  </tr>
</table>
<?php
} else { // pre_useridが有効
    // regist_confirmでのエラー表示
  
?>
<form method="post" action="member_signup.php">
  <input type="hidden" name="mode" value="regist_confirm">
  <input type="hidden" name="pre_userid" value="<?php print $pre_userid; ?>">
  <table>
    <caption>会員情報登録フォーム</caption>
    <tr>
      <td class="item">メールアドレス：</td>
      <?php if($pre_useridflg!=1){?>
      <td><?php print $email; ?><input type="hidden" name="input_email" value="<?php print $email; ?>"></td>
      <?php }else{ ?>
      <td><input type="text" size="30" name="input_email"></td>
      <?php } ?>
    </tr>
    <tr>
      <td class="item">パスワード：</td>
      <td><input type="password" size="30" name="input_password" >&nbsp;&nbsp;※ 6文字以上16文字以下</td>
    </tr>
    <tr>
      <td class="item">名前：</td>
      <td><input type="text" size="30" name="input_name"></td>
    </tr>
    <tr>
      <td class="item">チーム/学校：</td>
      <?php if($pre_useridflg!=1){?>
      <td><input type="text" size="30" name="input_team"></td>
      <?php }else{ ?>
      <td><?php print $team; ?><input type="hidden" name="input_team" value="<?php print $team; ?>"></td>
      <?php } ?>
    </tr>
  </table>
  <div><input type="submit" value=" 確認フォームへ"></div>
</form>
<?php
}
?>