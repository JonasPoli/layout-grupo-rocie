# Evitar Bugs — Grupo Rocie (Symfony 7 + AssetMapper + TailwindBundle)

Este documento registra os erros encontrados durante a montagem do projeto e como foram corrigidos. Use como referência em projetos futuros com a mesma stack.

---

## 1. Doctrine sem comandos disponíveis após instalação

**❌ Problema:**
Após instalar o `doctrine/orm` e `doctrine/doctrine-bundle`, o comando `doctrine:database:create` retornava erro dizendo que o namespace não existia, mesmo com o bundle no `composer.json`.

**✅ Solução:**
O `php bin/console cache:clear` resolveu o problema. O Symfony não detecta novos bundles até que o cache seja limpo. Sempre limpar o cache após instalar bundles.

```bash
php bin/console cache:clear
```

---

## 2. VichUploaderBundle e LiipImagineBundle ausentes do `bundles.php`

**❌ Problema:**
Os bundles `VichUploaderBundle` e `LiipImagineBundle` foram instalados via Composer, mas **não foram automaticamente registrados** no `config/bundles.php`, pois não possuem Symfony Flex recipe configurada.

**✅ Solução:**
Adicionar manualmente no `config/bundles.php`:

```php
Vich\UploaderBundle\VichUploaderBundle::class => ['all' => true],
Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
```

**Regra geral:** Bundles sem recipe Flex devem ser registrados manualmente.

---

## 3. Configuração do Doctrine com PostgreSQL num projeto MySQL

**❌ Problema:**
O `config/packages/doctrine.yaml` gerado pelo Symfony vinha com a opção `identity_generation_preferences` para PostgreSQL:

```yaml
identity_generation_preferences:
    Doctrine\DBAL\Platforms\PostgreSQLPlatform: identity
```

Isso causa erros ao usar MySQL.

**✅ Solução:**
Remover completamente o bloco `identity_generation_preferences` do `doctrine.yaml` quando o banco for MySQL.

---

## 4. Tailwind v4 vs v3 — Sintaxe de CSS incompatível

**❌ Problema (tentativa 1):**
Usado `@import "tailwindcss"` (sintaxe do Tailwind v4), mas o `SymfonyCasts TailwindBundle` usa o binário do **Tailwind v3** internamente. O build falhou com:

```
Error: Failed to find 'tailwindcss'
```

**❌ Problema (tentativa 2):**
Voltado para `@tailwind base; @tailwind components; @tailwind utilities;` (sintaxe correta para v3), mas as classes customizadas foram definidas com `@apply` usando modificadores com barra (`bg-primary/10`, `bg-white/10`). O Tailwind v3 tem problemas ao usar `@apply` com classes que contêm `/` (opacidade).

**✅ Solução:**
Usar `@tailwind base; @tailwind components; @tailwind utilities;` no topo do CSS e **escrever as classes customizadas em CSS puro**, sem `@apply`. Reservar `@apply` apenas para classes simples sem modificadores de opacidade ou responsividade.

```css
/* ✅ Correto — CSS puro */
.btn-primary {
    display: inline-flex;
    background-color: #1C3D76;
    color: white;
    /* ... */
}

/* ❌ Problemático com Tailwind v3 */
.btn-primary {
    @apply bg-primary/90 hover:bg-primary/80 text-white;
}
```

---

## 5. `encore_entry_link_tags` em projeto com AssetMapper

**❌ Problema:**
Os templates Twig foram criados com as funções do Webpack Encore:

```twig
{{ encore_entry_link_tags('app') }}
{{ encore_entry_script_tags('app') }}
```

Mas o projeto usa **AssetMapper** (não Encore), portanto essas funções não existem e lançam `UndefinedFunctionException`.

**✅ Solução:**
Substituir pelas funções corretas do AssetMapper:

```twig
{# CSS — aponta para assets/styles/app.css #}
<link rel="stylesheet" href="{{ asset('styles/app.css') }}">

{# JS — usa o importmap do AssetMapper #}
{{ importmap('app') }}
```

**Como identificar qual sistema está em uso:**
```bash
php bin/console debug:config framework asset_mapper
# Se retornar configuração → AssetMapper
# Se der erro → provavelmente Encore
```

---

## 6. Security.yaml — Loop infinito de redirecionamento no login

**❌ Problema:**
A rota `/admin/login` estava protegida pelo próprio firewall `main` com `access_control: { path: ^/admin, roles: ROLE_ADMIN }`. Isso causava um loop infinito: usuário não autenticado → redireciona para `/admin/login` → acesso bloqueado → redireciona para `/admin/login` → ...

**✅ Solução:**
Criar um firewall separado que exclui a rota de login da proteção:

```yaml
# config/packages/security.yaml
firewalls:
    dev:
        pattern: ^/(_(profiler|wdt)|css|images|js)/
        security: false
    login:                          # ← Adicionar este firewall
        pattern: ^/admin/login$
        security: false
    main:
        lazy: true
        provider: app_user_provider
        form_login:
            login_path: app_login
            check_path: app_login
```

---

## 7. Doctrine — Entidades com relações circulares e ordem de remoção

**❌ Problema:**
Ao tentar fazer `DELETE FROM Product` diretamente via DQL no command de limpeza, a foreign key de `product_categories` (tabela de join ManyToMany) bloqueava a exclusão.

**✅ Solução:**
Respeitar a ordem de exclusão das entidades — excluir primeiro as entidades dependentes e depois as principais. Ou usar `cascade: ['remove']` nas relações OneToMany no mapeamento Doctrine, para que o ORM gerencie a ordem automaticamente.

---

## 8. Tailwind — Classes de cores customizadas não geradas sem `content` paths

**❌ Problema:**
Classes como `bg-primary`, `text-primary`, `bg-rocie-accent` eram definidas no `tailwind.config.js` mas **não eram geradas no CSS final** porque o Tailwind purge não as encontrava nos templates.

**✅ Verificação:**
```bash
grep "bg-primary" var/tailwind/app.built.css
```

**✅ Solução:**
Garantir que o `tailwind.config.js` tenha os paths corretos no `content`:

```js
content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
],
```

E as cores estejam definidas no `theme.extend.colors`:

```js
colors: {
    'primary': {
        DEFAULT: '#1C3D76',
        light: '#2651a0',
        dark: '#132c55',
    },
}
```

---

## 9. Logo ausente — Imagem estática não existe

**❌ Problema:**
Os templates referenciam `{{ asset('images/logo.png') }}` mas o arquivo não existe em `public/images/logo.png`, resultando em imagem quebrada no header e footer.

**✅ Solução:**
Copiar a logo do cliente para `public/images/logo.png`:

```bash
cp docs/images/logoredondo.png public/images/logo.png
```

Ou criar o diretório e colocar a imagem correta:

```bash
mkdir -p public/images
cp [origem]/logo.png public/images/logo.png
```

---

## Checklist de Setup Rápido

Use este checklist para novos projetos Symfony 7 com a mesma stack:

- [ ] Configurar `.env.local` com `DATABASE_URL` MySQL antes de rodar qualquer comando
- [ ] Remover `identity_generation_preferences` do `doctrine.yaml` se banco for MySQL
- [ ] Registrar manualmente bundles sem Flex recipe (`VichUploader`, `LiipImagine`) no `bundles.php`
- [ ] Limpar cache após instalar bundles: `php bin/console cache:clear`
- [ ] CSS: usar `@tailwind base/components/utilities` (v3), **não** `@import "tailwindcss"` (v4)
- [ ] CSS: evitar `@apply` com modificadores de opacidade (`/10`, `/20`); preferir CSS puro
- [ ] Templates: usar `asset('styles/app.css')` + `importmap('app')`, **não** funções do Encore
- [ ] Security: criar firewall `login` separado para excluir rota de login do `access_control`
- [ ] Logo: copiar arquivo para `public/images/logo.png` antes de subir o servidor
- [ ] Verificar se Tailwind gerou as classes: `grep "bg-primary" var/tailwind/app.built.css`

---

## 10. Twig — `string[0]` causa erro em vez de pegar o primeiro caractere

**❌ Problema:**
`{{ app.user.username[0]|upper }}` lança `Impossible to access a key ("0") on a string variable`.

Em Twig, strings não são acessíveis por índice numérico como em PHP.

**✅ Solução:**
```twig
{{ app.user.username|first|upper }}
```

---

## 11. `doctrine:migrations:migrate` marca como executada mas não cria tabelas

**❌ Problema:**
O banco foi recriado (drop + create), mas ao rodar `doctrine:migrations:migrate` o comando dizia "já na versão mais recente" porque a tabela `doctrine_migration_versions` persistia com os registros da execução anterior. As tabelas do sistema não foram criadas, mas o Doctrine achava que estavam.

**✅ Solução:**
Usar `doctrine:schema:create` ao invés de `doctrine:migrations:migrate` quando o banco está vazio:

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:create        # ← cria tudo do zero
```

> **Atenção:** `doctrine:schema:create` não atualiza tabelas existentes — só cria. Para atualizar use `doctrine:schema:update --force`. Para produção, sempre prefira migrations.

---

## 12. Login com `SameOriginCsrfTokenManager` no Symfony 7

**❌ Problema:**
O Symfony 7 usa por padrão o `SameOriginCsrfTokenManager`, que valida CSRF por headers `Origin`/`Referer` e **não** por valor de token de sessão. A função `{{ csrf_token('authenticate') }}` retorna a string literal `"csrf-token"` como placeholder — isso é comportamento esperado, não um bug.

O login continuava falhando pois o `POST /admin/login` retornava HTTP 200 (falha) em vez de HTTP 302 (sucesso com redirect), mesmo com o token correto.

**✅ Causa real:** o banco de dados foi recriado e a tabela `user` não existia — o Symfony não encontrava o usuário e falhava silenciosamente retornando para a página de login.

**✅ Lição:** sempre verificar se o banco e as tabelas existem antes de depurar problemas de autenticação. Rodar:

```bash
php bin/console doctrine:query:sql "SHOW TABLES"
```

---

## 13. Firewall `login` com `security: false` quebra a sessão e o CSRF

**❌ Problema:**
Para evitar redirect loop no login, foi criado um firewall separado com `security: false`:

```yaml
login:
    pattern: ^/admin/login$
    security: false   # ← ERRADO: mata a sessão nessa rota
```

Isso desativa completamente o componente de segurança na rota, incluindo o gerenciador de sessão. Resultado: o CSRF token não era gerado corretamente e a autenticação falhava.

**✅ Solução correta:**
Manter apenas o firewall `main` e usar `access_control` com `PUBLIC_ACCESS` para a rota de login:

```yaml
firewalls:
    dev:
        pattern: ^/(_(profiler|wdt)|css|images|js)/
        security: false
    main:                       # ← apenas um firewall principal
        lazy: true
        form_login:
            login_path: app_login
            check_path: app_login

access_control:
    - { path: ^/admin/login$,  roles: PUBLIC_ACCESS }  # ← permite acesso público
    - { path: ^/admin/logout$, roles: PUBLIC_ACCESS }
    - { path: ^/admin,         roles: ROLE_ADMIN }
```

---

## Checklist de Setup Rápido (Atualizado)

- [ ] Configurar `.env.local` com `DATABASE_URL` MySQL antes de rodar qualquer comando
- [ ] Remover `identity_generation_preferences` do `doctrine.yaml` se banco for MySQL
- [ ] Registrar manualmente bundles sem Flex recipe (`VichUploader`, `LiipImagine`) no `bundles.php`
- [ ] Limpar cache após instalar bundles: `php bin/console cache:clear`
- [ ] CSS: usar `@tailwind base/components/utilities` (v3), **não** `@import "tailwindcss"` (v4)
- [ ] CSS: evitar `@apply` com modificadores de opacidade (`/10`, `/20`); preferir CSS puro
- [ ] Templates: usar `asset('styles/app.css')` + `importmap('app')`, **não** funções do Encore
- [ ] Security: usar `access_control: PUBLIC_ACCESS` na rota de login, **não** firewall separado com `security: false`
- [ ] Logo: copiar arquivo para `public/images/logo.png` antes de subir o servidor
- [ ] Verificar se tabelas existem: `php bin/console doctrine:query:sql "SHOW TABLES"`
- [ ] Usar `doctrine:schema:create` para banco vazio (não `migrations:migrate` se o banco foi recriado do zero)
- [ ] Em Twig, usar `|first` ao invés de `[0]` para pegar o primeiro caractere de uma string
- [ ] Gerar dados: `php bin/console app:generate-dummy-data`
- [ ] Criar admin: `php bin/console app:create-admin admin SuaSenha`
