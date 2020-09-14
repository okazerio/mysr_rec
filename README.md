# 概要
ECサイトのポートフォリオとなります。<br>
仮想のオンラインレコード販売サイトを制作しました。<br>
PHPとMySQLを用いて、画面設計・要件定義・データベース設計まで<br>
1からフルスクラッチでつくりました。<br>
シンプルな動的サイトを作成したため、不完全な実装となりますことを<br>
予めご了承ください。<br>

# 成果物
こちらからアクセスできます。<br>
https://mysorerecords.herokuapp.com<br>
<br>
<strong>デモ（商品ページ　→　カートページ　→　コンファームページのフロー）</strong><br>
![mysore](https://user-images.githubusercontent.com/70677663/93037484-7e634000-f67d-11ea-8866-e48c041aaa0d.gif)<br>
<br>
<strong>画面遷移図</strong><br>
![Untitled Diagram](https://user-images.githubusercontent.com/70677663/93047030-6f3cbc00-f696-11ea-9ea6-1d81b2e86a5f.png)<br>
<br>
<strong>データベースER図</strong><br>
![dbDiagram](https://user-images.githubusercontent.com/70677663/93055273-9b136e00-f6a5-11ea-9649-e29de2f45718.png)<br>



<p>ログイン時のIDおよびPW</p>
<ul>
<li>管理者　： ID: admin　PW: admin<br>
<li>ユーザー： ID: user 　 PW: user<br>
</ul>
当該サイトは、ログインしなくてもゲストとして購入まで行えます。<br>
権限は次の通りです。<br>
管理者は管理者ページとユーザーページの両方にアクセスできます。<br>
ユーザーはユーザーページのみで、ゲストもユーザーと同様です。<br>
ユーザーとゲストとの違いは、カートページでの購入者情報を入力するか<br>
しないかの違いとなります。（ゲストは入力必須です。）<br>


# 機能一覧
<p>【ユーザーページ】</p>
<ul>
<li>ユーザーの登録
<li>ユーザーのログイン・ログアウト
<li>商品一覧の表示
<li>商品一覧のページネーション
<li>カートの登録・変更・削除（ユーザー登録なしでもゲストとして可能）
<li>セッションの使用（画面左上にログインしたユーザーの「ID」を表示、カート内容の保存）
<li>クッキーの使用（画面左上にログインしていないゲストのカート内容の保存）
</ul>
<p>【管理者ページ】</p>
<ul>
<li>アイテムの登録・変更・削除
<li>アイテム画像をデータベースに登録（1MB以下、JPEG/PNGのみ）
<li>ユーザーの登録・変更・削除
<li>ユーザー一覧のページネーション
<li>天気予報をWeb APIで取得(インド/カリフォルニア/東京)
</ul>

# 開発環境・言語等
<ul>
<li>OS： Mac OSX 10.15.6
<li>使用言語： HTML/CSS, PHP, MySQL, JavaScript
<li>ローカル環境： MAMP
</ul>
