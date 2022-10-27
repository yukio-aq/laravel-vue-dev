# laravel9とVue3

## 環境構築

前提：Dockerをインストール済みで起動してる状態

### 😀Macの場合…
フォルダを作成し右クリックでターミナルを開く<br>
プロジェクト作成コマンドを入力
~~~ 
curl -s "https://laravel.build/{project-name}" | bash
~~~
Laravel sailでのプロジェクトが作成される


<br>


### 😀Windowsの場合…<br>
まずWSL2(Ubuntu)での環境構築が必要<br>
参考：https://chigusa-web.com/blog/wsl2-win11/<br>
~~~ 
wsl -l -v と入力し

  NAME                  STATE.    VERSION
* Ubuntu.               Running.  2
  docker-desktop-data.  Running.  2
  docker-desktop        Running.  2

となっていればOK
~~~

Ubuntuからプロジェクト作成コマンドを入力<br>
※Windows TerminalのタブからUbuntuを開くと通常通りコピペコマンドが使えるのでオススメ※
~~~ 
curl -s "https://laravel.build/{project-name}" | bash
~~~
Laravel sailでのプロジェクトが作成される

※curlが使えない場合はこちらを入力
~~~
$ sudo apt install -y curl
~~~


<br>


### 😀phpMyAdminの導入
~~~
# For more information: https://laravel.com/docs/sail
version: '3'
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.1
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.1/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '${APP_PORT:-80}:80'
            - '${VITE_PORT:-5173}:${VITE_PORT:-5173}'
        environment:
            WWWUSER: '${WWWUSER}'
            LARAVEL_SAIL: 1
            XDEBUG_MODE: '${SAIL_XDEBUG_MODE:-off}'
            XDEBUG_CONFIG: '${SAIL_XDEBUG_CONFIG:-client_host=host.docker.internal}'
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - mysql
            - redis
            - meilisearch
            - mailhog
            - selenium
    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: "%"
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
        networks:
            - sail
        healthcheck:
            test: ["CMD", "mysqladmin", "ping", "-p${DB_PASSWORD}"]
            retries: 3
            timeout: 5s
    redis:
        image: 'redis:alpine'
        ports:
            - '${FORWARD_REDIS_PORT:-6379}:6379'
        volumes:
            - 'sail-redis:/data'
        networks:
            - sail
        healthcheck:
            test: ["CMD", "redis-cli", "ping"]
            retries: 3
            timeout: 5s
    meilisearch:
        image: 'getmeili/meilisearch:latest'
        ports:
            - '${FORWARD_MEILISEARCH_PORT:-7700}:7700'
        volumes:
            - 'sail-meilisearch:/meili_data'
        networks:
            - sail
        healthcheck:
            test: ["CMD", "wget", "--no-verbose", "--spider",  "http://localhost:7700/health"]
            retries: 3
            timeout: 5s
    mailhog:
        image: 'mailhog/mailhog:latest'
        ports:
            - '${FORWARD_MAILHOG_PORT:-1025}:1025'
            - '${FORWARD_MAILHOG_DASHBOARD_PORT:-8025}:8025'
        networks:
            - sail
    selenium:
        image: 'selenium/standalone-chrome'
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        volumes:
            - '/dev/shm:/dev/shm'
        networks:
            - sail
    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        depends_on:
            - mysql
        ports:
            - 8888:80
        environment:
            PMA_USER: '${DB_USERNAME}'
            PMA_PASSWORD: '${DB_PASSWORD}'
            PMA_HOST: mysql
        networks:
            - sail
networks:
    sail:
        driver: bridge
volumes:
    sail-mysql:
        driver: local
    sail-redis:
        driver: local
    sail-meilisearch:
        driver: local

~~~
をdocker-compose.ymlにコピペ。


<br>


### 😀Sailコマンドの登録
./vendor/bin/sail up -dと毎回打ち込むのは面倒なので簡略化します
~~~
echo $SHELL とターミナルで入力
~~~

/bin/zsh と表示された場合
~~~
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.zshrc
~~~

/bin/bash と表示された場合
~~~
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
~~~

~~~
exec $SHELL -l
~~~

~~~
sail up -d
sail down
~~~
以上のコマンドで起動・終了ができればOK


### 😀Vueの導入

前提：sail up -dで起動している状態

~~~
sail npm i @vitejs/plugin-vue
~~~
これでpackage.jsonに@vitejs/plugin-vueが追加される。

vite.config.jsを以下のように修正
~~~
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
});
~~~

開発
~~~
sail npm run dev
~~~
本番
~~~
sail npm run build
~~~

<br>


以上で構築できたはず
