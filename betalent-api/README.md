# 🏦 Multi-Gateway Payment API (Laravel)

## 📌 Sobre
Este projeto é uma API RESTful para gerenciamento de pagamentos com múltiplos gateways, seguindo ordem de prioridade e fallback em caso de falha. Desenvolvido como parte do **Teste Prático Back-end BeTalent**.

---

## ✅ Tecnologias
- **Laravel 12.x**
- **MySQL**
- **Sanctum** (Autenticação)
- **Docker** (MySQL + Gateways Mock)
- **PHPUnit** (TDD)
- **Postman** (Collection para testes)

---

## 🚀 Como rodar o projeto

### 1. Clone o repositório
```bash
git clone https://github.com/rvwierzba/teste-pratico-backend.git
cd teste-pratico-backend/betalent-api
```

### 2. Instale dependências
```bash
composer install
```

### 3. Configure o .env
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:GERAR_COM_ARTISAN_KEY:GENERATE
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=betalent
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Gere a chave da aplicação
```bash
php artisan key:generate
```

### 5. Rode migrations
```bash
php artisan migrate
```

### 6. Suba os mocks dos gateways
```bash
docker run -p 3001:3001 -p 3002:3002 matheusprotzen/gateways-mock
```
#### Sem autenticação:
```bash
docker run -p 3001:3001 -p 3002:3002 -e REMOVE_AUTH='true' matheusprotzen/gateways-mock
```

### 7. Rode a aplicação
```bash
php artisan serve
```
#### A API estará disponível em:
```bash
http://127.0.0.1:8000/api
```

## 🔐 Autenticação

- Login: POST /api/login
```json
{
  "email": "admin@email.com",
  "password": "123456"
}
```

- Resposta:
```json
{
  "token": "seu-token-aqui",
  "role": "ADMIN"
}

```

- Use o token no header:
```bash
Authorization: Bearer seu-token-aqui
```

## 🔌 Rotas Principais

### Compra com fallback

- POST /api/purchase:
```json
{
  "client_name": "João Silva",
  "client_email": "joao@email.com",
  "products": [
    { "id": 1, "quantity": 2 },
    { "id": 2, "quantity": 1 }
  ],
  "card_number": "5569000000006063",
  "cvv": "010"
}
```
- Resposta esperada:
```json
{
  "id": 10,
  "status": "SUCCESS",
  "gateway_id": 1,
  "external_id": "abc123",
  "amount": 3000
}
```

### Reembolso
POST /api/transactions/{id}/refund

CRUD Usuários
GET /api/users
POST /api/users
PATCH /api/users/{id}
DELETE /api/users/{id}

CRUD Produtos
GET /api/products
POST /api/products

### Gateways
Ativar/desativar: PATCH /api/gateways/{id}/toggle
Alterar prioridade: PATCH /api/gateways/{id}/priority

## 🧪 Testes
```bash
php artisan test
```

## ✅ Multi-Gateways

Gateway 1: http://localhost:3001
Gateway 2: http://localhost:3002

### Collection Postman:
multigateways_payment_api.json (já incluso no repositório)

## 📌 Roles

- ADMIN: tudo
- MANAGER: gerenciar produtos e usuários
- FINANCE: gerenciar produtos e reembolso
- USER: realizar compras


## 🛠 Estrutura do Projeto
```bash
app/
  Http/
    Controllers/
    Middleware/
  Models/
  Services/
routes/
  api.php
database/
  migrations/
```


## ✅ Checklist Final

 ☑ Laravel instalado
 ☑ Sanctum configurado
 ☑ Migrations rodadas
 ☑ Gateways mock via Docker
 ☑ Rotas implementadas
 ☑ Lógica multi-gateway com fallback
 ☑ Middleware de roles
 ☑ README completo
