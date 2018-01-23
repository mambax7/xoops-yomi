# Yomi-Search [XOOPS] について

Yomi-Search [XOOPS] は、原作者の WonderLink-Yomi 様およびYomi-Search PHP化プロジェクト様の許可を頂いて、nao-pon がXOOPSモジュールへ改造して再配布しています。

ご使用にあたり以下の注意事項・利用規定を必ずお読みください。


## 注意事項

* オリジナルのYomi-Search4のログから変換する場合、アクセスカウントは無効になります。
* 著作権は原作者のyomiさんにありますので、Yomi-Search [XOOPS] の使用にあたっても、オリジナルの利用規定に必ず従ってください。
* 利用規定にもありますが画面下に表示されるリンクは消さないでください。
* Yomi-Search [XOOPS] に関する質問等は、http://xoops.hypweb.net/ で受け付けます。（yomiさんおよびYomi-Search PHP化プロジェクトさんのサイトへは、間違ってもしないようにお願いします。）


## 利用規定

以下のサイトへアクセスして利用規定をお読みください。(現在リンク切れ)

* トップページ http://yomi.pekori.to/
* 規約ページ http://yomi.pekori.to/kiyaku.html

(以下はWonderLink配布スクリプト利用規約より抜粋)

> 以下に挙げる規約を守ることができない場合には
> WonderLinkで配布しているスクリプトの使用はご遠慮ください。
> この規約に違反した場合には、WonderLinkはスクリプトの使用中止又は機能停止を
> 該当する使用者又はサーバ管理者に対して要請できるものとします。
> 
> WonderLinkで配布しているスクリプト（以下、配布スクリプト）は
> 各自の責任のもとにご利用ください。
> バグチェックなどは必ず行うようにしていますが、
> 配布スクリプトを使用したいかなる損害に対しても
> 作者(yomi)は一切の責任を負わないものとします。
> 
> 配布スクリプト(フリーソフトの場合)の
> バージョンアップやバグの修正は作者の義務ではありません。
> バグの修正やバージョンアップのリクエストはなるべく聞いていきたいと思いますが
> 作者の都合によりご期待に添えない場合もありますのでご了承ください。
> 
> 配布スクリプトの中にはCGIのトップページをHTMLで表示できるスクリプトが
> ありますが、（『Yomi-Search』や『P-Search』や『Yomi-Mailer』）
> このスクリプトで使用するトップページのWonderLinkへのリンクは
> 削除しないでください。リンクの右寄せ・左寄せ・色の変更、バナーでのリンクへの変更は禁止しません。
> リンクの文字もスクリプト名でなく『WonderLink』としていただいても結構です。
> 
> 商用サイト・非商用サイトに関わらずご自由に配布スクリプトを利用してください。
> 例えば、商品売買を目的とするホームページでリンク集や商品データベースとして
> 商用利用するのも許可しています。
> ただし、配布スクリプトを有償で配布することは許可しません。
> 
> 配布スクリプトはすべてのサーバでの動作を保証するわけではありません。
> サーバの設定などにより動作しない場合もありますのでご了承ください。

## インストール方法

* yomi_xoops.zip をダウンロードして解凍します。
* pl, log ディレクトリ内のすべての *.dev ファイルをリネームして .dev を取り除きます。
> 　(例)  
> 　　pl/cfg.php.dev         -> pl/cfg.php  
> 　　log/keyrank_ys.php.dev -> log/keyrank_ys.php  
> 　　log/look_mes.cgi.dev   -> log/look_mes.cgi  
* XOOPS の modules ディレクトリに yomi ディレクトリ以下すべてをアップロードしてPHPで書込み可能なパーミッションを設定します。
> 　(例)  
> 　　blocks/logos (777)  
> 　　pl/cfg.php (666)  
> 　　pl/other_cfg.php (666)  
> 　　log/内の index.html 以外のファイル (666)  
* XOOPSの管理者画面よりインストールをします。
* インストールが完了したら、XOOPSの管理者画面より Yomiサーチ のアイコンをクリックして管理者画面を開きます。
* 管理者パスワードは、ほとんど使いませんが、Yomiサーチの管理者画面の「環境設定」から管理者パスワードを設定してください。(Yomiサーチ独自でも結構です。)
* Yomiサーチの管理者画面の「環境設定」の各項目を確認してください。
* XOOPSのグループ管理で、アクセスさせる各グループの「モジュールアクセス権限」の Yomiサーチ にチェックを入れて登録してください。

　以上でインストール完了です。

 再配布者　nao-pon http://xoops.hypweb.net/
