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
デモ（商品ページ→カートページ→コンファームページのフロー）<br>
![mysore](https://user-images.githubusercontent.com/70677663/93037484-7e634000-f67d-11ea-8866-e48c041aaa0d.gif)
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
</ul>
<p>【管理者ページ】</p>
<ul>
<li>アイテムの登録・変更・削除（画像はJPEG/PNG）
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
