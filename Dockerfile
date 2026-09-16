Dockerfile
FROM php:8.2-apache

# SQLiteを使用するための拡張機能をインストール
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo_sqlite

# ApacheのRewriteモジュールを有効化（必要に応じて）
RUN a2enmod rewrite

# アプリケーションのファイルをコピー
COPY . /var/www/html/

# データベース書き込み権限の付与（SQLite用）
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80