
# Backend Challenge 20230105 by Coodesh

## Descrição do Projeto

O projeto tem como objetivo dar suporte à equipe de nutricionistas da empresa Fitness Foods LC, permitindo que eles revisem rapidamente as informações nutricionais dos alimentos publicados pelos usuários na aplicação móvel.

## Ferramentas Utilizadas

- PHP 8.2
- Laravel 10
- MySQL

## Link para Apresentação

[Assista à apresentação]()

## Instruções de Instalação

### Opção 1: Configuração Local (sem Docker)

1. **Requisitos**: Certifique-se de que você possui o PHP 8.2, MySQL versão 5.8 ou superior e o Composer instalados em sua máquina.

2. **Configuração do Ambiente**:
    - Rode o comando:
      ```bash
      cp .env.example .env
      ```
      Isso criará um arquivo `.env` para configuração.

3. **Configuração do Banco de Dados**:
    - Verifique se a porta do MySQL no arquivo `.env` é a mesma porta que está sendo utilizada pelo MySQL em sua máquina.
    - Crie um novo banco de dados chamado `codesh_test`.

4. **Instalação de Dependências**:
    - Execute o comando:
      ```bash
      composer install
      ```

5. **Migrações**:
    - Para criar os esquemas necessários, rode o comando:
      ```bash
      php artisan migrate
      ```

6. **Inicializando o Servidor**:
    - Inicie o servidor com o comando:
      ```bash
      php artisan serve
      ```

### Opção 2: Execução com Docker

Para facilitar a configuração e o gerenciamento do ambiente de desenvolvimento, você pode executar o projeto usando Docker. Siga os passos abaixo:

1. **Requisitos**: Certifique-se de que você possui o Docker e o Docker Compose instalados em sua máquina.

2. **Configuração do Ambiente**:
    - Rode o comando:
      ```bash
      cp .env.example .env
      ```
      Isso criará um arquivo `.env` para configuração.

3. **Configuração do Banco de Dados**:
    - No arquivo `.env`, ajuste a configuração do banco de dados para usar o serviço do MySQL que será definido no Docker Compose. A porta padrão é 3306.
    - **Docker Compose**:
      Edite o arquivo chamado `docker-compose.yml` na raiz do projeto se quiser alterar algum parâmetro para testar em outra DB, porém ele irá criar uma automaticamente.

4. **Inicializando o Servidor**:
    - Inicie os serviços do Docker com o comando:
      ```bash
      docker-compose up -d
      ```
    - O aplicativo estará disponível em `http://localhost:8000`.

## Utilizando a API REST

### Autenticação

Para autenticar suas requisições, crie um Token e adicione-o no HEADER de autorização como X-API-KEY. Use o seguinte comando:
```bash
docker-compose run app php artisan api-key:generate
```

## Forçando a execução dos métodos CRON

### Download dos Produtos

Para fazer o download do JSON de produtos, use o seguinte comando:
```bash
docker-compose run app php artisan product:download
```

### Importação dos Produtos

Para fazer a importação dos produtos, use o seguinte comando:
```bash
docker-compose run app php artisan product:import
```

### Endpoints Disponíveis

#### Health da Aplicação

- **Método**: GET
- **URL**: `http://127.0.0.1:8000/api`

#### Listagem dos Produtos Importados

- **Método**: GET
- **URL**: `http://127.0.0.1:8000/api/products`
- **HEADER**:
- **X-Page**: `esse parâmetro pode ser utilizado para definir a página dos resultados`

#### Detalhes de um Produto

- **Método**: GET
- **URL**: `http://127.0.0.1:8000/api/products/{codigoDoProduto}`

#### Atualização de um Produto

- **Método**: PUT
- **URL**: `http://127.0.0.1:8000/api/products/{codigoDoProduto}`
- **Payload Exemplo**:
  ```json
  {
      "status": "published",
      "url": "https://world.openfoodfacts.org/product/20221126",
      "name": "Madalenas quadradas",
      "brands": "La Cestera",
      "categories": "Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas",
      "labels": "Contém glúten, Contém derivados de ovos, Contém ovos",
      "cities": "Braga",
      "purchasePlaces": "Braga, Portugal",
      "stores": "Lidl",
      "traces": "Frutos de casca rija, Leite, Soja, Sementes de sésamo, Produtos à base de sementes de sésamo",
      "nutriScore": 17,
      "nutriScoreGrade": "d",
      "mainCategory": "en:madeleines",
      "imageUrl": "https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg"
  }
  ```

#### Criação de um Produto (EXTRA)

- **Método**: CREATE
- **URL**: `http://127.0.0.1:8000/api/product`
- **Payload Exemplo**:
  ```json
  {
      "status": "published",
      "url": "https://world.openfoodfacts.org/product/20221126",
      "name": "Madalenas quadradas",
      "brands": "La Cestera",
      "categories": "Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas",
      "labels": "Contém glúten, Contém derivados de ovos, Contém ovos",
      "cities": "Braga",
      "purchasePlaces": "Braga, Portugal",
      "stores": "Lidl",
      "traces": "Frutos de casca rija, Leite, Soja, Sementes de sésamo, Produtos à base de sementes de sésamo",
      "nutriScore": 17,
      "nutriScoreGrade": "d",
      "mainCategory": "en:madeleines",
      "imageUrl": "https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg"
  }
  ```


#### Inativar um Produto

- **Método**: DELETE
- **URL**: `http://127.0.0.1:8000/api/products/{codigoDoProduto}`

## Logs de Importação

Após executar o comando de importação, você pode acompanhar os logs com informações no arquivo:
```
storage/logs/imports.log
```

## Observações Finais

Existe um arquivo chamado `API Test Collection.postman_collection.json` na raiz do projeto que poderá ser usado para importar para o Postman os requests da API.
Qualquer dúvida ou problemas entre em contato comigo.
