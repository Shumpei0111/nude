# セッティングメモ

## MAMPで複数ドメインを扱いたい

変更するファイルは3つ

- httpd.conf
  - /Applications/MAMP/conf/apache

- httpd-vhosts.conf
  - /Applications/MAMP/apache/extra

- hosts
  - /private/etc

### どこを変えるか

- httpd.conf

575行目のコメントアウトを外す

```
# Virtual hosts
Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf
```

- httpd-vhosts.conf

初期設定されているサンプルはコメントアウトする

追加したい分だけ以下を記入

```
<VirtualHost *:80>
  DocumentRoot "/Applications/MAMP/htdocs/yakiniku/www"
  ServerName yakiniku.com
  <Directory "/Applications/MAMP/htdocs/yakiniku/www">
    AllowOverride All
  </Directory>
</VirtualHost> 

<VirtualHost *:80>
  DocumentRoot "/Applications/MAMP/htdocs/nude/html"
  ServerName nude.com
  <Directory "/Applications/MAMP/htdocs/nude/html">
    AllowOverride All
  </Directory>
</VirtualHost> 

```

- hosts

`sudo vim /private/etc/hosts`

適当にドメインをlocalhostに当てる

```
127.0.0.1 yakiniku.com
127.0.0.1 nude.com
```