# coachtechフリマ

Laravelを使用したフリマアプリケーションです。
ユーザー登録から商品出品・購入までを一通り行うことができます。

## 概要

本アプリは「coachtech」の模擬案件課題として開発されました。
ユーザーが商品を出品・購入できるフリマアプリで、認証には **Laravel Fortify**、決済には **Stripe API** を利用しています。
Docker 環境で動作するため、ローカルで起動・テストが可能です。

---

## 環境構築
DockerとLaravelの設定手順です。

### Dockerコンテナの構築

1. リポジトリをクローン
```bash
git clone git@github.com:ayako1179/item-market.git
cd item-market
```
2. DockerDesktopアプリを立ち上げる
3. コンテナをビルド・起動
```bash
docker-compose up -d --build
```

> *MacのM1・M2チップのPCの場合
ビルド時に以下のエラーが発生する場合があります：
`no matching manifest for linux/arm64/v8 in the manifest list entries`
対処法として、`docker-compose.yml`の`myspl`内に以下を追加してください。
```yaml
platform: linux/x86_64
# この行を追加
```

### Laravelアプリケーション構築
1. PHPコンテナに入る
```bash
docker-compose exec php bash
```
2. 依存関係をインストール
```bash
composer install
```
3. 環境ファイルを作成
「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
```bash
cp .env.example .env
```
4. .envに以下の環境変数を設定
``` text
APP_URL=http://localhost:8081
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# Stripe APIキー（Stripe管理画面から取得）
STRIPE_KEY=pk_test_51SCE2qBUa3WtvACItw3gJRqItsjNuallxPpqQCLwXHpPfGErS2vCPNtOikz5nkNP8WkonHaLqFDQocKCyYwu2EKA00INYsu6ot
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxx
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```
8. ストレージのシンボリックリンクを作成
``` bash
php artisan storage:link
```
> Fortify と Stripe は `compser.json` に含まれているため、
追加インストールは不要です。（`composer install`で自動導入されます）

---

## 使用技術/実行環境
| 項目           | 内容                                   |
| :------------- | :------------------------------------- |
| 言語           | PHP 8.1.33                             |
| フレームワーク | Laravel 8.83.8                         |
| データベース   | MySQL 8.0.26                           |
| サーバー       | Nginx                                  |
| コンテナ管理   | Docker / docker-compose                |
| 認証           | Laravel Fortify                        |
| 決済           | Stripe API（クレジットカード決済対応） |

---

## 機能概要
- ユーザー登録・ログイン（Fortify）
- 商品出品・購入・Sold表示
- プロフィール編集（画像・住所・ユーザー名）
- いいね機能・コメント機能
- Stripeを利用したクレジットカード支払い決済（コンビニ払いはDB登録のみ）

---

## 決済仕様
- クレジットカード決済
  Stripe の安全な決済画面に遷移し、決済成功後に商品一覧画面へ戻ります。
  キャンセル時は購入画面に再遷移します。
- コンビニ払い
  Stripe は使用せず、注文情報を即時DB保存して商品一覧画面に遷移します。

---

## 認証機能（Laravel Fortify）
- 新規登録・ログイン・ログアウトを実装。
- Fortifyは `composer.json` に定義済みのため、手動導入不要。

---

## テスト環境構築と実行方法
本アプリでは主要機能を自動検証するためのテスト環境を整備しています。
`PHPUnit` により Feature / Unit テストを実行可能です。

### テスト用データベース設定
`.env.testing` を作成し、以下の設定を記述してください。
```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=item_market_test
DB_USERNAME=root
DB_PASSWORD=root

# Stripeキー（必要に応じてコメントアウト）
# STRIPE_KEY=pk_test_************************
# STRIPE_SECRET=sk_test_************************
```

### テスト実行手順
```bash
# PHPコンテナに入る
docker-compose exec php bash

# 全テストを実行
php artisan test

# 結果をファイルに出力（任意）
php artisan test > tests/results.txt
```

### テスト結果（2025年10月時点）
```makefile
Tests:  39 passed
Time:   3.72s
```
> テストでは「商品出品・購入・コメント・認証」など主要機能を網羅的に検証しています。
`.env.testing` のDB（`item_market_test`）を使用するため、本番データは破壊されません。

---

## テストユーザー情報（動作確認用）
アプリケーションの動作確認に利用できる初期ユーザーです。
（PHPUnitテストで使用されるユーザーとは別データです）
| ユーザー名 | メールアドレス     | パスワード | 出品商品     |
| ---------- | ------------------ | ---------- | ------------ |
| testuser   | `test@example.com` | `12345678` | 腕時計・革靴 |

> `users` テーブルと `profiles` テーブルのシーディング時に自動作成されます。
ログイン後にプロフィール情報が紐づいていることを確認できます。

---

## ER図
![alt](docs/er.png)

---

## URL一覧
- アプリケーション：http://localhost:8081/
- phpMyAdmin:：http://localhost:8080/

---

## 作者情報
作成者：Ayako  
GitHub：[https://github.com/ayako1179](https://github.com/ayako1179)
