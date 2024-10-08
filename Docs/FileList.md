# 既存ファイル情報

## A. ファイル一覧
| No. | ファイル名 | 概要 |
| ---- | ---- | ---- |
| 00 | `rsv-header.php` | **共通ヘッダー** |
| 01 | `rsv-footer.php` | **共通フッター** |
| 02 | `rsv-functions.php` | [**各種関数**](#02-%E5%90%84%E7%A8%AE%E9%96%A2%E6%95%B0--rsv-functionsphp) |
| 03 | `rsv-style.css` | **スタイルシート** |
|  |  |  |
| **利用者画面** |  |  |
| 10 | `user-screen.php` | [予約カレンダー](#10-%E4%BA%88%E7%B4%84%E3%82%AB%E3%83%AC%E3%83%B3%E3%83%80%E3%83%BC--rsv-headerphp) |
| 11 | `user_form_input.php` | [予約フォーム](#11-%E4%BA%88%E7%B4%84%E3%83%95%E3%82%A9%E3%83%BC%E3%83%A0--user_form_inputphp) |
| 12 | `user_form_output.php` | [予約完了表示](#12-%E4%BA%88%E7%B4%84%E5%AE%8C%E4%BA%86%E8%A1%A8%E7%A4%BA--user_form_outputphp) |
|  |  |  |
| **管理者画面** |  |  |
| 20 | `admin-screen.php` | [管理者画面トップ](#20-%E7%AE%A1%E7%90%86%E8%80%85%E7%94%BB%E9%9D%A2%E3%83%88%E3%83%83%E3%83%97--admin-screenphp) |
| 21 | `admin-rsv-cld.php` | [予約状況確認タブ](#21-%E4%BA%88%E7%B4%84%E7%8A%B6%E6%B3%81%E7%A2%BA%E8%AA%8D%E3%82%BF%E3%83%96--admin-rsv-cldphp) |
| 22 | `admin-temp-rsv.php` | [検索タブ](#22-%E6%A4%9C%E7%B4%A2%E3%82%BF%E3%83%96--admin-temp-rsvphp) |
| 23 | `admin-new-rsv.php` | [新規予約タブ](#23-%E6%96%B0%E8%A6%8F%E4%BA%88%E7%B4%84%E3%82%BF%E3%83%96--admin-new-rsvphp) |
| 24 | `login-input.php` | [ログイン](#24-%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3--login-inputphp) |
| 25 | `logout.php` | [ログアウト](#25-%E3%83%AD%E3%82%B0%E3%82%A2%E3%82%A6%E3%83%88--logoutphp) |
|  |  |  |
|  **――内部処理** |  |  |
| 30 | `login-output.php` | [ログイン処理](#30-%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3%E5%87%A6%E7%90%86--login-outputphp) |
| 31 | `logout-output.php` | [ログアウト処理](#31-%E3%83%AD%E3%82%B0%E3%82%A2%E3%82%A6%E3%83%88%E5%87%A6%E7%90%86--logout-outputphp) |
|  |  |  |
|  **――フレーム内** |  |  |
| 40 | `default.php` | [デフォルト表示(予約情報表示前)](#40-%E3%83%87%E3%83%95%E3%82%A9%E3%83%AB%E3%83%88%E8%A1%A8%E7%A4%BA%E4%BA%88%E7%B4%84%E6%83%85%E5%A0%B1%E8%A1%A8%E7%A4%BA%E5%89%8D--defaultphp) |
| 41 | `detailed-info.php` | [予約詳細情報表示](#41-%E4%BA%88%E7%B4%84%E8%A9%B3%E7%B4%B0%E6%83%85%E5%A0%B1%E8%A1%A8%E7%A4%BA--detailed-infophp) |
| 42 | `admin-form-input.php` | [新規予約フォーム](#42-%E6%96%B0%E8%A6%8F%E4%BA%88%E7%B4%84%E3%83%95%E3%82%A9%E3%83%BC%E3%83%A0--admin-form-inputphp) |
<br>

***
## B. 各ファイル概説
#### 02. **各種関数** : `rsv-functions.php`
  + 詳細情報表示のためのセッション開始
  + カレンダーのためのセッション開始
  + トップページリンク
  + 予約番号生成
  + XSS対策
  + カレンダー開始日設定
  + データベース接続情報
  + 施設名称及び枠時間の取得
  + カレンダー表示
  <br>

#### 10. **予約カレンダー** : `rsv-header.php`
  1. **[関数]** カレンダー用日付設定
  2. **[関数]** データベース接続設定
  3. 施設データ取得
  4. 説明文表示
  5. **[関数]** カレンダー表示
  <br>

#### 11. **予約フォーム** : `user_form_input.php`
  1. 曜日配列初期化
  2. **[関数]** データベース接続設定
  3. データ取得
  4. 連続予約可能数チェック
  5. 予約情報表示
  6. 予約フォーム表示
  7. **[関数]** トップページリンク
  8. **[Js]** 予約時間の動的表示
  <br>

#### 12. **予約完了表示** : `user_form_output.php`
  1. 日付変数初期化
  2. 曜日配列初期化
  3. **[関数]** 施設名情報及び枠時間情報取得
  4. **[関数]** データベース接続設定
  5. データ取得
  6. 予約重複確認
  7. **[関数]** 予約番号発行
  8. 予約実行(データベース反映)
  9. 予約情報表示
  10. **[関数]** トップページリンク
  <br>
  
***
#### 20. **管理者画面トップ** : `admin-screen.php`
  1. **[ファイル呼出]** ログイン画面表示
  2. **[ファイル呼出]** ログイン処理
  3. 各タブ表示
      + **[ファイル呼出]** 予約状況確認タブ
      + **[ファイル呼出]** 検索タブ
      + **[ファイル呼出]** 新規予約タブ
      + **[ファイル呼出]** 休日設定タブ
      + **[ファイル呼出]** ログアウト
  4. (スタイルシート)※rsv-style.cssが読めないため
  5. **[js]** タブ切替機能
  <br>
  
#### 21. **予約状況確認タブ** : `admin-rsv-cld.php`
  1. **[関数]** カレンダー用日付設定
  2. **[関数]** データベース接続設定
  3. **[ファイル呼出]** フレーム表示
  4. **[関数]** カレンダー表示
  <br>
  
#### 22. **検索タブ** : `admin-temp-rsv.php`
  1. **[ファイル呼出]** フレーム表示
  2. 検索フォーム表示
  3. 曜日配列初期化
  4. **[関数]** データベース接続設定
  5. データ取得
  6. 検索結果テーブル表示
  7. **[js]** Ajax予約詳細表示
  <br>

#### 23. **新規予約タブ** : `admin-new-rsv.php`
  1. **[関数]** カレンダー用日付設定
  2. **[関数]** データベース接続設定
  3. **[ファイル呼出]** フレーム表示
  4. **[関数]** カレンダー表示
  <br>

#### 24. **ログイン** : `login-input.php`
  1. ログインフォーム表示
  <br>

#### 25. **ログアウト** : `logout.php`
  1. ログアウトフォーム表示
  <br>

***
#### 30. **ログイン処理** : `login-output.php`
  1. **[関数]** データベース接続設定
  2. データ照合
  3. ログインセッション開始
  <br>

#### 31. **ログアウト処理** : `logout-output.php`
  1. ログインセッション破棄
  <br>

***
#### 40. **デフォルト表示(予約情報表示前)** : `default.php`
  1. テキスト表示
  <br>

#### 41. **予約詳細情報表示** : `detailed-info.php`
  1. 曜日配列初期化
  2. **[関数]** データベース接続設定
  3. 差分抽出
  4. 情報更新反映
  5. 情報取得(予約者ID)
  6. 情報取得(予約情報)
  7. 検索結果テーブル表示
  8. 情報更新フォーム表示
  <br>

#### 42. **新規予約フォーム** : `admin-form-input.php`
  1. 予約実行
      1. 日付変
      2. 数初期化
      3. 曜日配列初期化
      4. **[関数]** データベース接続設定
      5. **[関数]** 施設名情報及び枠時間情報取得
      6. データ取得
      7. 予約重複確認
      8. **[関数]** 予約番号発行
      9. 予約実行(データベース反映)
  2. 予約フォーム
      1. 曜日配列初期化
      2. **[関数]** データベース接続設定
      3. データ取得
      4. 連続予約可能数チェック
      5. 予約情報表示
      6. 予約フォーム
      7. **[Js]** 予約時間の動的表示
  <br>

## C. 修正箇所

## D. 既存ファイル懸案
+ファイル命名規則がひどい
+変数・関数の命名規則がひどい
+コメントがない
