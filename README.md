# coachtechフリマ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:ayako1179/item-market.git
2. cd item-market
3. DockerDesktopアプリを立ち上げる
4. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
APP_URL=http://localhost:8081
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# Stripe APIキー（管理画面から取得）
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
8. シンボリックリンク作成
``` bash
php artisan storage:link
```

## 使用技術(実行環境)
- PHP8.1.33
- Laravel8.83.8
- MySQL8.0.26
- Docker / docker-compose

## 外部サービス / ライブラリ
- Laravel Fortify（認証機能）
- Stripe（決済処理）

## 機能概要
- ユーザー認証（Laravel Fortifyを利用）
- 商品一覧・詳細表示
- 商品出品・購入
- プロフィール編集
- いいね機能 / コメント機能
- 決済機能（Stripeを利用して「カード支払い」「コンビニ払い」の決済画面に接続）

## ユーザー認証について
本アプリでは **Laravel Fortify** を利用して、認証機能を実装しています。

### 提供される機能
- ユーザー新規登録
- ログイン / ログアウト

### 導入パッケージ
``` bash
composer require laravel/fortify
```

## 決済機能について
本アプリでは **Stripe** を利用し、以下の決済画面に接続できます。
- クレジットカード決済
- コンビニ決済

### 導入パッケージ
``` bash
composer require stripe/stripe-php
```

## ER図
![alt](docs/er.png)

## URL
- 開発環境：http://localhost:8081/
- phpMyAdmin:：http://localhost:8080/
