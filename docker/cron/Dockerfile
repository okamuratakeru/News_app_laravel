FROM php:8.3-fpm

# 1) cron と必要ツールをインストール
RUN apt-get update \
  && apt-get install -y cron \
  # MySQL関連のパッケージを追加
  && apt-get install -y default-mysql-client libpq-dev \
  # PDO MySQLドライバをインストール
  && docker-php-ext-install pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# 2) crontab 設定ファイルを配置
COPY cron.root /etc/cron.d/laravel-cron

# 3) 権限設定＆ログを stdout に接続
RUN chmod 0644 /etc/cron.d/laravel-cron \
 && ln -sf /proc/1/fd/1 /var/log/cron.log

# 4) 作業ディレクトリをアプリケーション直下に
WORKDIR /src


# 5) フォアグラウンドで cron を起動
CMD ["cron", "-f"]

