# Sistema de Fornecedores

## Índice
1. [Introdução](#introdução)
2. [Recursos](#recursos)
3. [Instalação](#instalação)
    1. [Com Docker](#com-docker)
    2. [Sem Docker](#sem-docker)
4. [Uso](#uso)
5. [Configuração](#configuração)
6. [Contribuição](#contribuição)
7. [Licença](#licença)
8. [Contato](#contato)

## Introdução
Este é um sistema de gerenciamento de fornecedores através de. Ele permite que você cadastre, edite, remova e visualize fornecedores. O sistema também permite que você faça buscas de fornecedores, filtrando por CNPJ e utilizando paginação.
> [⬆️Índice](#índice)

## Recursos
- Cadastro de fornecedores
- Edição de fornecedores
- Remoção de fornecedores
- Visualização de fornecedores
- Busca de fornecedores por CNPJ
> [⬆️Índice](#índice)

## Instalação

### Com Docker

Você deve começar clonando o repositório do projeto em sua máquina local.

```bash
git clone https://github.com/LeoPersan/teste-dev-php.git
```

Em seguida, você deve entrar na pasta do projeto e criar um arquivo `.env`.

```bash
cd teste-dev-php
cp .env.example .env
```

Agora você deve instalar as dependências do projeto.

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
> **Nota:** Adicione o argumento `--dev` ao final do comando para instalar as dependências de desenvolvimento e poder rodar testes e análises estáticas.

Agora você deve construir a imagem do Docker e iniciar o container.

```bash
./vendor/bin/sail up -d
```

Em seguida, você deve gerar a chave de aplicação.

```bash
./vendor/bin/sail artisan key:generate
```

Agora você deve criar banco de dados.

```bash
./vendor/bin/sail artisan migrate
```

Por fim, você deve acessar o sistema através do navegador.

```
http://localhost
```
> [⬆️Índice](#índice)

### Sem Docker

#### Requisitos
- Linux
- PHP 8.2
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - Filter
  - Hash
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Session
  - Tokenizer
  - XML
- Composer
- MySQL 8.0

Você deve começar clonando o repositório do projeto em sua máquina local.

```bash
git clone https://github.com/LeoPersan/teste-dev-php.git
```

Em seguida, você deve entrar na pasta do projeto e criar um arquivo `.env`.

```bash
cd teste-dev-php
cp .env.example .env
```

Troque a seguinte variável de ambiente no arquivo `.env`.

```bash
DB_HOST=127.0.0.1
```

Agora você deve instalar as dependências do projeto.

```bash
composer install
```
> **Nota:** Adicione o argumento `--dev` ao final do comando para instalar as dependências de desenvolvimento e poder rodar testes e análises estáticas.

Agora você deve gerar a chave de aplicação.

```bash
php artisan key:generate
```

Agora você deve criar banco de dados.

```bash
php artisan migrate
```

Por fim, você deve acessar o sistema através do navegador.

```
http://localhost
```
> [⬆️Índice](#índice)

## Uso
Você pode acessar o sistema através do navegador. Acessando a URL `http://localhost/docs/api` você terá acesso a documentação da API.

Você pode utilizar as páginas de documentação para testar as rotas da API.
> [⬆️Índice](#índice)

## Comandos Úteis

- Rodar testes
```bash
./vendor/bin/sail artisan test
# OU
php artisan test
```
- Rodar análise estática
```bash
./vendor/bin/sail php ./vendor/bin/phpstan analyse
# OU
./vendor/bin/phpstan analyse
```
- Rodar Correção de Estilo
```bash
./vendor/bin/sail php ./vendor/bin/pint
# OU
./vendor/bin/pint
```
> [⬆️Índice](#índice)
