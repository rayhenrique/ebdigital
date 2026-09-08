# 📚 Manual Completo de Instalação e Configuração — Caderneta EBD Online
### *Guia Prático Passo a Passo: Windows (Iniciantes), Linux (Ubuntu/Debian) e VPS (CloudPanel)*

Seja muito bem-vindo ao manual oficial da **Caderneta EBD Online** (Igreja Evangélica Assembleia de Deus). Este documento foi escrito pensando em você que **nunca instalou um servidor web antes**, detalhando cada etapa, clique, tela e comando de maneira clara, simples e didática.

---

## 📑 Índice do Manual

1. [Entendendo as Ferramentas (O que é cada coisa?)](#1-entendendo-as-ferramentas-o-que-é-cada-coisa)
2. [Guia 1: Instalação no Windows (Ambiente Local para Leigos)](#guia-1-instalação-no-windows-ambiente-local-para-leigos)
   - [Etapa 1.1: Instalar o Pacote Visual C++ Redistributable (Essencial)](#etapa-11-instalar-o-pacote-visual-c-redistributable-essencial)
   - [Etapa 1.2: Instalar e Configurar o WampServer ou XAMPP](#etapa-12-instalar-e-configurar-o-wampserver-ou-xampp)
   - [Etapa 1.3: Ativar as Extensões Obrigatórias do PHP](#etapa-13-ativar-as-extensões-obrigatórias-do-php)
   - [Etapa 1.4: Instalar o Git para Windows](#etapa-14-instalar-o-git-para-windows)
   - [Etapa 1.5: Instalar o Node.js e NPM](#etapa-15-instalar-o-nodejs-e-npm)
   - [Etapa 1.6: Adicionar o PHP às Variáveis de Ambiente do Windows (PATH)](#etapa-16-adicionar-o-php-às-variáveis-de-ambiente-do-windows-path)
   - [Etapa 1.7: Instalar o Composer (Gerenciador do PHP)](#etapa-17-instalar-o-composer-gerenciador-do-php)
   - [Etapa 1.8: Criar o Banco de Dados no phpMyAdmin](#etapa-18-criar-o-banco-de-dados-no-phpmyadmin)
   - [Etapa 1.9: Baixar o Projeto e Configurar o `.env`](#etapa-19-baixar-o-projeto-e-configurar-o-env)
   - [Etapa 1.10: Instalar Dependências e Ligar o Sistema](#etapa-110-instalar-dependências-e-ligar-o-sistema)
3. [Guia 2: Instalação no Linux (Ubuntu / Debian)](#guia-2-instalação-no-linux-ubuntu--debian)
   - [Etapa 2.1: Atualizar o Sistema e Pacotes Básicos](#etapa-21-atualizar-o-sistema-e-pacotes-básicos)
   - [Etapa 2.2: Instalar o PHP 8.3 e Extensões](#etapa-22-instalar-o-php-83-e-extensões)
   - [Etapa 2.3: Instalar o MySQL Server](#etapa-23-instalar-o-mysql-server)
   - [Etapa 2.4: Instalar Composer e Node.js](#etapa-24-instalar-composer-e-nodejs)
   - [Etapa 2.5: Baixar o Projeto e Subir o Banco](#etapa-25-baixar-o-projeto-e-subir-o-banco)
   - [Etapa 2.6: Ajustar Permissões de Pastas](#etapa-26-ajustar-permissões-de-pastas)
   - [Etapa 2.7: Configurar Nginx ou Apache com VirtualHost](#etapa-27-configurar-nginx-ou-apache-com-virtualhost)
4. [Guia 3: Instalação em VPS com CloudPanel (Servidor em Produção)](#guia-3-instalação-em-vps-com-cloudpanel-servidor-em-produção)
   - [Etapa 3.1: Apontar o Domínio (DNS Tipo A)](#etapa-31-apontar-o-domínio-dns-tipo-a)
   - [Etapa 3.2: Criar Site e Banco de Dados no CloudPanel](#etapa-32-criar-site-e-banco-de-dados-no-cloudpanel)
   - [Etapa 3.3: Emitir Certificado SSL Grátis (HTTPS)](#etapa-33-emitir-certificado-ssl-grátis-https)
   - [Etapa 3.4: Conectar via SSH e Baixar o Sistema](#etapa-34-conectar-via-ssh-e-baixar-o-sistema)
   - [Etapa 3.5: Configurar o `.env` de Produção](#etapa-35-configurar-o-env-de-produção)
   - [Etapa 3.6: Rodar Migrações e Compilar Front-End](#etapa-36-rodar-migrações-e-compilar-front-end)
   - [Etapa 3.7: Configurar o Cron Job no Painel](#etapa-37-configurar-o-cron-job-no-painel)
   - [Etapa 3.8: Script Automático de Atualização (`deploy.sh`)](#etapa-38-script-automático-de-atualização-deploysh)
5. [Perguntas Frequentes & Resolução de Problemas (FAQ)](#perguntas-frequentes--resolução-de-problemas-faq)
6. [Credenciais Padrão de Demonstração](#credenciais-padrão-de-demonstração)

---

## 1. Entendendo as Ferramentas (O que é cada coisa?)

Antes de começarmos a instalar, vamos entender em poucas palavras o papel de cada peça no funcionamento da Caderneta EBD:

- 🐘 **PHP (versão 8.2 ou superior)**: É a linguagem de programação em que o sistema foi construído. Pense nele como o motor do carro.
- 🗄️ **MySQL / MariaDB**: É o banco de dados onde todas as informações ficam guardadas (alunos, congregações, presenças, usuários, turmas e ofertas).
- 🌐 **Servidor Web (Apache ou Nginx)**: É o atendente que recebe os pedidos do navegador quando alguém digita o endereço e entrega as páginas prontas na tela.
- 📦 **WampServer / XAMPP**: É um pacote "tudo em um" para Windows que instala o Apache, o PHP e o MySQL juntos com um clique só.
- 🐙 **Git**: É a ferramenta que permite baixar e atualizar o código-fonte do sistema direto do repositório no GitHub sem precisar baixar arquivos compactados manualmente.
- 🎼 **Composer**: É o gerenciador de bibliotecas do PHP. Ele baixa automaticamente todos os recursos modernos que o framework Laravel necessita.
- ⚡ **Node.js e NPM**: São responsáveis por compilar o visual moderno do sistema (Tailwind CSS, ícones vetoriais e scripts interativos).

---

## Guia 1: Instalação no Windows (Ambiente Local para Leigos)

Este guia foi feito para quem deseja rodar o sistema no seu próprio computador ou notebook com Windows.

```
       Computador Windows
 ┌───────────────────────────────┐
 │ 1. Visual C++ Redistributable │
 │ 2. WampServer (Apache/MySQL)  │
 │ 3. PHP 8.2+ nas Variáveis     │
 │ 4. Git + Composer + Node.js   │
 └──────────────┬────────────────┘
                ▼
  Caderneta EBD (http://127.0.0.1:8000)
```

---

### Etapa 1.1: Instalar o Pacote Visual C++ Redistributable (Essencial)

> [!CRITICAL]
> **Atenção:** 90% dos erros em que o WampServer ou XAMPP não iniciam no Windows (ficam amarelos ou vermelhos) acontecem porque o Windows não possui os pacotes da Microsoft Visual C++ instalados!

1. Acesse o site oficial de suporte da Microsoft:
   👉 [Microsoft Visual C++ Redistributable](https://learn.microsoft.com/pt-br/cpp/windows/latest-supported-vc-redist)
2. Baixe e instale a versão **x64 (64-bit)**:
   - Link direto: `https://aka.ms/vs/17/release/vc_redist.x64.exe`
3. Execute o instalador, avance e conclua a instalação.
4. Reinicie o computador se solicitado.

---

### Etapa 1.2: Instalar e Configurar o WampServer ou XAMPP

Você pode utilizar o **WampServer** ou o **XAMPP**. Ambos funcionam perfeitamente.

#### Opção A: Usando WampServer (Recomendado)
1. Acesse o site oficial: [wampserver.aviatechno.net](https://wampserver.aviatechno.net/) ou [sourceforge.net/projects/wampserver/](https://sourceforge.net/projects/wampserver/).
2. Baixe o instalador **WampServer 64 bits (x64)** contendo **PHP 8.2 ou PHP 8.3**.
3. Execute o instalador como Administrador:
   - Mantenha o diretório padrão sugerido: `C:\wamp64`.
   - Avance até a conclusão.
4. Abra o WampServer pelo menu Iniciar.
5. Observe o ícone do WampServer ao lado do relógio do Windows (na bandeja do sistema):
   - 🔴 **Vermelho**: Serviços parados.
   - 🟡 **Laranja**: Um serviço iniciou, mas outro falhou (geralmente porta ocupada).
   - 🟢 **Verde**: Perfeito! Apache e MySQL estão rodando com sucesso.

#### Opção B: Usando XAMPP (Alternativa Popular)
1. Acesse [apachefriends.org](https://www.apachefriends.org/pt_br/index.html).
2. Baixe o **XAMPP para Windows** com PHP 8.2+.
3. Instale na pasta `C:\xampp`.
4. Abra o **XAMPP Control Panel** e clique em **Start** ao lado de **Apache** e **MySQL** (ambos ficarão com fundo verde).

---

### Etapa 1.3: Ativar as Extensões Obrigatórias do PHP

O Laravel exige algumas extensões ativadas no PHP para criptografia, manipulação de imagens e banco de dados:

1. Clique com o botão esquerdo no ícone verde do WampServer junto ao relógio.
2. Navegue até: **PHP** > **Extensões PHP** (ou *PHP extensions*).
3. Verifique se as seguintes opções estão com a marcação de ativadas (com um visto verde ao lado):
   - `curl`
   - `fileinfo`
   - `gd`
   - `intl`
   - `mbstring`
   - `openssl`
   - `pdo_mysql`
   - `zip`
4. Se alguma estiver desmarcada, basta clicar sobre ela; o WampServer reiniciará os serviços automaticamente.

---

### Etapa 1.4: Instalar o Git para Windows

1. Acesse: [git-scm.com/download/win](https://git-scm.com/download/win).
2. Baixe a versão **64-bit Git for Windows Setup**.
3. Execute o instalador:
   - Pode clicar em **Next** mantendo todas as opções padrão sugeridas.
   - Na opção de editor, pode manter o padrão ou escolher VS Code / Notepad.
4. Clique em **Install** e aguarde a finalização.
5. Para testar se deu certo:
   - Pressione as teclas `Windows + R`, digite `cmd` e aperte `Enter`.
   - Digite `git --version` e pressione `Enter`. Você verá algo como: `git version 2.x.x`.

---

### Etapa 1.5: Instalar o Node.js e NPM

O Node.js compila os arquivos de estilo Tailwind CSS da aplicação:

1. Acesse: [nodejs.org](https://nodejs.org/).
2. Baixe a versão identificada como **LTS (Recomendada para a maioria dos usuários)**.
3. Execute o instalador baixado, aceite os termos e avance clicando em **Next** até **Install**.
4. Para testar:
   - Abra o terminal do Windows (`cmd` ou `PowerShell`).
   - Digite `node -v` (deve exibir `v20.x` ou `v22.x`).
   - Digite `npm -v` (deve exibir `10.x` ou superior).

---

### Etapa 1.6: Adicionar o PHP às Variáveis de Ambiente do Windows (PATH)

Para que você possa digitar comandos do Laravel (como `php artisan`) em qualquer janela de terminal, o Windows precisa saber onde o arquivo `php.exe` está guardado:

1. Descubra onde está o PHP do seu Wamp ou XAMPP:
   - Se usa WampServer: costuma ser `C:\wamp64\bin\php\php8.3.x` (verifique o nome exato da pasta dentro de `bin\php`).
   - Se usa XAMPP: é `C:\xampp\php`.
2. No menu Iniciar do Windows, digite: **variáveis de ambiente**.
3. Clique na opção: **Editar as variáveis de ambiente do sistema**.
4. Na janela que se abrir, clique no botão **Variáveis de Ambiente...** (na parte inferior).
5. Na caixa inferior chamada **Variáveis do sistema**, procure pela linha com o nome **Path** e clique nela duas vezes (ou clique em *Editar*).
6. Na lista de caminhos, clique no botão **Novo** à direita.
7. Cole o caminho da pasta do PHP (exemplo: `C:\wamp64\bin\php\php8.3.0` ou `C:\xampp\php`).
8. Clique em **OK**, depois em **OK** novamente e feche a janela.
9. Feche qualquer terminal aberto e abra um novo `cmd`. Digite `php -v`. Deve aparecer a versão do PHP!

---

### Etapa 1.7: Instalar o Composer (Gerenciador do PHP)

1. Acesse: [getcomposer.org/download/](https://getcomposer.org/download/).
2. Clique no link **Composer-Setup.exe** para baixar o instalador oficial do Windows.
3. Execute o instalador:
   - Selecione a opção **Install for all users** (Recomendado).
   - Avance até a tela onde ele pergunta pelo caminho do PHP. Ele deve detectar automaticamente o caminho adicionado no passo anterior (ex: `C:\wamp64\bin\php\php8.3.x\php.exe`).
   - Se não detectar, clique em **Browse** e aponte para o arquivo `php.exe`.
4. Conclua a instalação.
5. No terminal, teste digitando `composer --version`.

---

### Etapa 1.8: Criar o Banco de Dados no phpMyAdmin

1. Abra seu navegador (Chrome, Edge, etc.) e acesse:
   👉 **`http://localhost/phpmyadmin`**
2. Se solicitar login:
   - **Usuário**: `root`
   - **Senha**: deixe em branco (sem senha) se estiver usando WampServer ou XAMPP padrão.
3. No menu superior ou lateral esquerdo, clique em **Bancos de dados** (ou *Databases* / *Novo*).
4. No campo **Nome do banco de dados**, digite: `ebdigital`.
5. No campo de agrupamento (Collation), selecione: `utf8mb4_unicode_ci` (ou `utf8mb4_general_ci`).
6. Clique no botão **Criar**. Pronto! O banco vazio está criado.

---

### Etapa 1.9: Baixar o Projeto e Configurar o `.env`

Agora vamos colocar o código dentro da pasta web do servidor:

1. Abra o terminal (PowerShell ou Git Bash) e entre na pasta de projetos do seu servidor:
   - Se você usa WampServer:
     ```powershell
     cd C:\wamp64\www
     ```
   - Se você usa XAMPP:
     ```powershell
     cd C:\xampp\htdocs
     ```
2. Clone o repositório do projeto:
   ```bash
   git clone https://github.com/rayhenrique/ebdigital.git
   ```
3. Entre na pasta recém-baixada:
   ```bash
   cd ebdigital
   ```
4. Crie o arquivo de configuração `.env` copiando o modelo de exemplo:
   ```powershell
   copy .env.example .env
   ```
5. Abra o arquivo `.env` no Bloco de Notas ou VS Code (`notepad .env`) e confirme as linhas do banco:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ebdigital
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(No WampServer/XAMPP padrão, a senha do root é vazia, então `DB_PASSWORD=` deve ficar sem nada após o igual).*
6. Salve e feche o arquivo.

---

### Etapa 1.10: Instalar Dependências e Ligar o Sistema

Com o terminal ainda aberto na pasta `C:\wamp64\www\ebdigital` (ou `C:\xampp\htdocs\ebdigital`), execute os seguintes comandos:

```bash
# 1. Baixar todas as dependências do Laravel
composer install

# 2. Gerar a chave de segurança criptográfica
php artisan key:generate

# 3. Criar as tabelas e dados iniciais no banco de dados
php artisan migrate --seed

# 4. Instalar as dependências visuais e compilar o Tailwind CSS
npm install
npm run build

# 5. Iniciar o servidor local da Caderneta EBD
php artisan serve
```

🎉 **Parabéns!** O terminal exibirá:
```text
   INFO  Server running on [http://127.0.0.1:8000].
```
Abra o navegador e acesse: **`http://127.0.0.1:8000`**.  
Você já verá a tela de login com os botões de preenchimento rápido!

---

## Guia 2: Instalação no Linux (Ubuntu / Debian)

Este guia é voltado para servidores Linux locais ou máquinas virtuais (Ubuntu 22.04 / 24.04 LTS ou Debian 11/12).

```
        Servidor Linux (Ubuntu/Debian)
 ┌────────────────────────────────────────┐
 │ 1. apt update & upgrade               │
 │ 2. PPA ondrej/php (PHP 8.3 + módulos) │
 │ 3. MySQL 8 / MariaDB Server            │
 │ 4. Node.js LTS + Composer oficial      │
 │ 5. Nginx/Apache com DocRoot em /public │
 └────────────────────────────────────────┘
```

---

### Etapa 2.1: Atualizar o Sistema e Pacotes Básicos

Conecte-se no terminal do Linux ou via SSH e atualize os pacotes:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git unzip zip software-properties-common ca-certificates lsb-release
```

---

### Etapa 2.2: Instalar o PHP 8.3 e Extensões

No Ubuntu, adicionamos o repositório mantido por Ondřej Surý para ter acesso às versões mais modernas e seguras do PHP:

```bash
# Adicionar repositório do PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instalar PHP 8.3 com todas as extensões necessárias
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-curl \
    php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl \
    php8.3-gd php8.3-tokenizer
```

---

### Etapa 2.3: Instalar o MySQL Server

```bash
# Instalar o servidor MySQL
sudo apt install -y mysql-server

# Iniciar e habilitar o serviço
sudo systemctl start mysql
sudo systemctl enable mysql

# Acessar o console do MySQL como administrador
sudo mysql
```

Dentro do terminal do MySQL (`mysql>`), crie o banco e o usuário dedicado:

```sql
CREATE DATABASE ebdigital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ebdigital'@'localhost' IDENTIFIED BY 'SuaSenhaSeguraAqui123!';
GRANT ALL PRIVILEGES ON ebdigital.* TO 'ebdigital'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### Etapa 2.4: Instalar Composer e Node.js

```bash
# 1. Instalar o Composer globalmente
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# 2. Instalar Node.js LTS (NodeSource)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

### Etapa 2.5: Baixar o Projeto e Subir o Banco

Vamos clonar o sistema na pasta padrão `/var/www/ebdigital`:

```bash
# 1. Entrar no diretório web
cd /var/www

# 2. Clonar o projeto
sudo git clone https://github.com/rayhenrique/ebdigital.git
cd ebdigital

# 3. Configurar o arquivo de ambiente
sudo cp .env.example .env
sudo nano .env
```

No editor `nano`, configure a conexão com o banco criado no passo 2.3:
```ini
APP_NAME="Caderneta EBD"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://seu-ip-ou-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebdigital
DB_USERNAME=ebdigital
DB_PASSWORD=SuaSenhaSeguraAqui123!
```
*(Para salvar: `Ctrl + O`, `Enter` e `Ctrl + X`).*

Agora execute a instalação das dependências:
```bash
# Instalar pacotes do PHP
sudo composer install --no-dev --optimize-autoloader

# Gerar chave da aplicação
sudo php artisan key:generate

# Rodar migrações e criar tabelas com carga inicial
sudo php artisan migrate --force --seed

# Compilar assets do front-end
sudo npm install
sudo npm run build
```

---

### Etapa 2.6: Ajustar Permissões de Pastas

O servidor web (`www-data`) precisa gravar logs, sessões e arquivos de cache:

```bash
sudo chown -R www-data:www-data /var/www/ebdigital
sudo chmod -R 775 /var/www/ebdigital/storage /var/www/ebdigital/bootstrap/cache
```

---

### Etapa 2.7: Configurar Nginx ou Apache com VirtualHost

#### Exemplo com Nginx (Recomendado):
Crie o arquivo de configuração do site:
```bash
sudo nano /etc/nginx/sites-available/ebdigital
```
Cole a configuração abaixo (substituindo `ebd.suaigreja.com.br` pelo seu domínio ou IP):

```nginx
server {
    listen 80;
    server_name ebd.suaigreja.com.br;
    root /var/www/ebdigital/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Ative o site e reinicie o Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/ebdigital /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## Guia 3: Instalação em VPS com CloudPanel (Servidor em Produção)

O **CloudPanel** é um dos painéis de controle mais rápidos e modernos para hospedagem de aplicações PHP/Laravel em servidores VPS (DigitalOcean, Hetzner, Contabo, AWS, Linode, Hostinger, etc.).

Dados reais da VPS de referência da Assembleia de Deus:
- **Domínio**: `https://ebd.adteotoniovilela.com.br`
- **IP do Servidor**: `72.60.142.2`

---

### Etapa 3.1: Apontar o Domínio (DNS Tipo A)

No seu gerenciador de domínio (Cloudflare, Registro.br, Hostinger, etc.):
1. Crie uma nova entrada DNS:
   - **Tipo**: `A`
   - **Nome / Host**: `ebd` *(resultará em `ebd.adteotoniovilela.com.br`)*
   - **Destino (IPv4)**: `72.60.142.2` (ou o IP da sua VPS)
   - **TTL**: `Automático` ou `3600`.
2. Salve e aguarde alguns minutos para a propagação.

---

### Etapa 3.2: Criar Site e Banco de Dados no CloudPanel

1. Acesse o CloudPanel no navegador: `https://SEU_IP_VPS:8443`.
2. Vá em **Sites** > clique no botão **Adicionar Site** > escolha **Criar um Site PHP**.
3. Preencha os campos:
   - **Inscrição**: Selecione o template `Laravel 13` (ou `PHP`).
   - **Nome do domínio**: `ebd.adteotoniovilela.com.br`
   - **Versão do PHP**: Escolha `PHP 8.3` ou superior.
   - **Usuário do site**: `adteotoniovilela-ebd`
   - **Senha**: Crie uma senha segura e guarde-a.
4. Clique em **Criar**.
5. Clique na aba **Definições** do site criado e confirme se o diretório raiz está apontando para:
   ```text
   ebd.adteotoniovilela.com.br/public
   ```
6. Clique na aba **Bancos de dados** > **Adicionar banco de dados**:
   - **Nome do banco**: `ebdigital`
   - **Usuário do banco**: `ebdigital`
   - **Senha**: Gere e guarde a senha (você usará no `.env`).
   - Clique em **Adicionar banco de dados**.

---

### Etapa 3.3: Emitir Certificado SSL Grátis (HTTPS)

1. No CloudPanel, entre no site e clique na aba **SSL/TLS**.
2. Clique em **Ações** > **Novo Certificado Let's Encrypt**.
3. Marque o domínio e clique em **Criar e Instalar**.
4. O cadeado verde de segurança estará ativo e se renovará sozinho a cada 90 dias.

---

### Etapa 3.4: Conectar via SSH e Baixar o Sistema

Abra o terminal do seu computador (PowerShell, Prompt de Comando ou Git Bash):

```bash
# Conectar no servidor com o usuário do site criado no CloudPanel:
ssh adteotoniovilela-ebd@72.60.142.2
```
*(Digite a senha do usuário do site criada no CloudPanel).*

Ao entrar, prepare a pasta do site e clone o código:
```bash
# Entrar na pasta do site
cd /home/adteotoniovilela-ebd/htdocs/ebd.adteotoniovilela.com.br

# Limpar os arquivos temporários padrão que o CloudPanel cria
rm -rf * .git*

# Clonar o repositório diretamente na pasta atual
git clone https://github.com/rayhenrique/ebdigital.git .
```

---

### Etapa 3.5: Configurar o `.env` de Produção

```bash
# Copiar o modelo
cp .env.example .env

# Abrir para editar
nano .env
```

Altere as configurações principais:
```ini
APP_NAME="Caderneta EBD"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=America/Maceio
APP_URL=https://ebd.adteotoniovilela.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebdigital
DB_USERNAME=ebdigital
DB_PASSWORD=SUA_SENHA_CRIADA_NO_CLOUDPANEL

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```
Para salvar no `nano`: tecle `Ctrl + O`, aperte `Enter` e depois saia com `Ctrl + X`.

---

### Etapa 3.6: Rodar Migrações e Compilar Front-End

Execute os comandos na ordem:

```bash
# 1. Gerar chave secreta
php artisan key:generate

# 2. Instalar dependências PHP otimizadas para produção
composer install --no-dev --optimize-autoloader

# 3. Compilar visual moderno (Tailwind / Vite)
npm install
npm run build

# 4. Publicar assets estáticos do Livewire
php artisan livewire:publish --assets

# 5. Criar as tabelas e dados iniciais
php artisan migrate --force --seed

# 6. Otimizar os caches do Laravel
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Garantir permissões de escrita
chmod -R 775 storage bootstrap/cache
```

Pronto! Acesse `https://ebd.adteotoniovilela.com.br` e o sistema já estará funcionando em produção!

---

### Etapa 3.7: Configurar o Cron Job no Painel

Para que as tarefas automáticas do sistema rodem (como expurgo de auditoria):

1. No CloudPanel, acesse o site `ebd.adteotoniovilela.com.br`.
2. Clique na aba **Cron Jobs** > **Adicionar Cron Job**.
3. Preencha:
   - **Descrição**: `Laravel Scheduler`
   - **Intervalo**: Selecione **A cada minuto** (`* * * * *`).
   - **Comando**:
     ```bash
     php /home/adteotoniovilela-ebd/htdocs/ebd.adteotoniovilela.com.br/artisan schedule:run >> /dev/null 2>&1
     ```
4. Salve a configuração.

---

### Etapa 3.8: Script Automático de Atualização (`deploy.sh`)

Sempre que você fizer melhorias no código do seu computador e enviar para o GitHub (`git push`), **não é necessário repetir todos os comandos**.

Basta conectar no terminal da VPS e rodar o script pronto incluído no projeto:

```bash
# 1. Conectar na VPS
ssh adteotoniovilela-ebd@72.60.142.2

# 2. Entrar na pasta e rodar o script
cd /home/adteotoniovilela-ebd/htdocs/ebd.adteotoniovilela.com.br
bash deploy.sh
```

O script faz tudo sozinho em segundos:
- Ativa o modo de manutenção temporário
- Puxa as atualizações do Git (`git pull`)
- Atualiza dependências do Composer e NPM
- Roda novas migrações de banco se houver
- Compila o front-end
- Limpa e renova todos os caches
- Desativa a manutenção e entrega a aplicação 100% pronta!

---

## Perguntas Frequentes & Resolução de Problemas (FAQ)

### 1. O WampServer fica com ícone Laranja ou Vermelho no Windows. O que fazer?
- **Causa mais comum**: Outro programa está usando a porta `80` (como Skype, IIS do Windows ou outro Apache/XAMPP).
- **Solução**:
  1. Feche o Skype ou programas semelhantes.
  2. No WampServer, clique com o botão esquerdo > **Apache** > **Service administration** > **Test Port 80** para descobrir qual programa está ocupando a porta.
  3. Você também pode mudar a porta do Apache para `8080` em: **Apache** > **Use a port other than 80**.

### 2. Ao digitar `php` no terminal do Windows dá erro: *"php não é reconhecido como um comando interno ou externo"*.
- **Causa**: O caminho da pasta do PHP não foi adicionado à variável `Path` do Windows.
- **Solução**: Siga com atenção o passo [Etapa 1.6 deste manual](#etapa-16-adicionar-o-php-às-variáveis-de-ambiente-do-windows-path). Lembre-se de fechar e reabrir o terminal após salvar para recarregar as configurações.

### 3. Deu erro 500 (Server Error) em Produção / VPS.
- **Causa 1**: Permissão de escrita nas pastas `storage` e `bootstrap/cache`.
  - **Solução**: Execute `chmod -R 775 storage bootstrap/cache`.
- **Causa 2**: Chave da aplicação vazia.
  - **Solução**: Execute `php artisan key:generate`.
- **Causa 3**: Credenciais de banco incorretas no `.env`.
  - **Solução**: Verifique se a senha do banco no `.env` bate exatamente com a senha gerada no CloudPanel.

### 4. A aplicação ficou presa na mensagem: *"Estamos realizando uma breve manutenção..."*
- **Causa**: O deploy foi interrompido antes de finalizar a etapa de saída da manutenção.
- **Solução**: Conecte na VPS e rode o comando:
  ```bash
  php artisan up
  ```

---

## 🔐 Credenciais Padrão de Demonstração

Após rodar o comando `php artisan migrate --seed`, você pode entrar com qualquer um dos seguintes usuários de teste:

| Perfil | E-mail de Acesso | Senha Padrão | O que pode fazer? |
| :--- | :--- | :--- | :--- |
| **Pastor / Administrador** | `admin@ebd.local` | `senha123` *(ou `password`)* | Acesso total ao campo, todas as congregações, gestão de usuários, senhas e auditoria. |
| **Secretário / Superintendente** | `secretario@ebd.local` | `senha123` *(ou `password`)* | Gestão de turmas, alunos, professores da sua congregação e relatórios. |
| **Professor 1 (Barnabé Silva)** | `professor1@ebd.local` | `senha123` *(ou `password`)* | Chamada dominical das classes de Adultos e Jovens. |
| **Professor 2 (Débora Oliveira)** | `professor2@ebd.local` | `senha123` *(ou `password`)* | Chamada dominical das classes de Adolescentes e Infantil. |

---

> 📖 *"Instrui o menino no caminho em que deve andar, e até quando envelhecer não se desviará dele."* — **Provérbios 22:6**  
> **Caderneta EBD Online** — Desenvolvido para a edificação e excelência na Escola Bíblica Dominical.
