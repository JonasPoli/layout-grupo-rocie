# Guia de Estilo: Formulários Administrativos

Este documento descreve os padrões de UI/UX utilizados no projeto. Ele deve ser fornecido a outras IAs para garantir que a geração de código siga a arquitetura e estética estabelecidas.

## 1. Stack de Tecnologias (Frontend)
Para replicar este visual, a outra IA deve saber que o projeto utiliza:

- **Framework**: Symfony 7+ (PHP) com arquitetura Monolítica.
- **Templating**: **Twig** (Server-Side Rendering).
- **CSS**: **Tailwind CSS** (Utilizando o plugin de formulários e Dark Mode via classe `.dark`).
- **Interatividade**: **Stimulus JS** (Parte do Symfony UX / Hotwire) para comportamentos dinâmicos sem SPAs complexas.
- **Componentes de UI**: **Shoelace** (Web Components embutidos como `<sl-button>`, `<sl-icon>`, `<sl-switch>`).
- **Editor de Texto**: **TinyMCE** para campos `rich-editor`.

## 2. Técnicas e Distribuição de Responsabilidades

O visual "Premium" é alcançado através da combinação estratégica de três camadas:

### A. Estrutura e "Skin" (Tailwind CSS)
A maior parte da estética (espaçamento, cores, sombras) vem puramente do Tailwind.
- **Layout de Cards**: Utilizamos `shadow-sm`, `rounded-lg` e `p-4` sobre fundos `bg-white` ou `bg-gray-50`.
- **Dark Mode**: Cada classe de cor tem sua variante `dark:`, garantindo que o formulário seja legível em ambos os temas.
- **Micro-interações**: Usamos `transition-colors` e `duration-200` em conjunto com `hover:bg-blue-50` para dar sensação de "clicabilidade".

### B. Componentes Ricos (Shoelace)
Usamos **Shoelace** para elementos que exigem comportamento e acessibilidade complexos:
- **Botões de Ação**: `<sl-button>` é usado para botões principais por oferecer estados de "loading", variantes consistentes e prefixos de ícones integrados.
- **Iconografia**: `<sl-icon>` carrega ícones SVG leves e consistentes.
- **Controles Especiais**: `<sl-switch>` e `<sl-select>` (em alguns casos) são usados para uma experiência de usuário mais "app-like".

### C. Lógica Híbrida (JavaScript + Tailwind)
Alguns componentes são "híbridos" (Inputs nativos estilizados com Tailwind e controlados por JS):
- **Selection Cards**: É a técnica mais importante. Usamos um `<input type="radio">` escondido dentro de um `label` grande. O JavaScript monitora o estado `checked` e aplica dinamicamente bordas e fundos do Tailwind (`border-blue-300 bg-blue-50`) ao elemento pai.
- **Filtros por Atributo**: Usamos `data-typologies` no HTML para que o JavaScript filtre elementos sem precisar de chamadas ao servidor (Ajax), tornando a interface instantânea.

---

## 3. Guia Visual de Campos
- **Fundo de Seção**: `bg-gray-50` / `dark:bg-slate-700`
- **Fundo de Card (Campo)**: `bg-white` / `dark:bg-slate-800`
- **Bordas**: `border-gray-200` / `dark:border-gray-700`
- **Destaque (Ação/Foco)**: `blue-500` / `blue-600`

---

## 2. Estrutura de Componentes

### 2.1 containers (Cards)
Agrupamos campos relacionados em containers para reduzir a carga cognitiva.

```html
<div class="mb-8 p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white border-b pb-2">Título da Seção</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Cards de Campo individuais aqui -->
    </div>
</div>
```

### 2.2 Select Fields Modernos
Evitamos o visual nativo do browser em favor de um estilo unificado.
- **Classes**: `w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-gray-600 outline-none focus:border-blue-500 rounded-md transition-colors`

### 2.3 Selection Cards (Radio/Checkbox Customizados)
Substituímos inputs pequenos por áreas clicáveis grandes.
- **Classes Base**: `cursor-pointer flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors`
- **Classes Ativas**: `bg-blue-50 dark:bg-slate-700 border-blue-300 dark:border-blue-600`
- **Comportamento**: Requer uma pequena lógica JS para alternar as classes de borda/fundo ao detectar o evento `change`.

---

## 3. Interatividade e Lógica

### Filtro Dinâmico (Product -> Typology)
Implementamos uma filtragem via atributos `data`.
- O Select de produto contém `data-typologies='["VALOR1", "VALOR2"]'`.
- Ao mudar o select, os cards de tipologia que não correspondem aos valores são ocultados (`display: none`).

### Remoção de Formatação (TinyMCE)
Para manter a integridade visual, incluímos um botão para remover cores e tamanhos de fonte fixos (`strip-font-color`), forçando o conteúdo a seguir os temas do sistema.

---

## 4. Super-Prompt para IA (The Premium Form Builder)

Para gerar novos componentes seguindo este padrão, utilize o prompt abaixo:

> Atue como um especialista em Tailwind CSS. Crie um formulário administrativo premium.
> 1. Use `bg-gray-50` para containers externos e `bg-white` para cards internos.
> 2. Implemente suporte total a Dark Mode (`dark:`).
> 3. Transforme botões de rádio em 'Selection Cards' (`label` com bordas e hover).
> 4. Use `p-2.5` e `rounded-md` em todos os inputs.
> 5. Adicione `transition-colors` em elementos interativos.
> 6. Mantenha os labels com `text-sm font-medium` e descrições com `text-xs text-gray-500`.

---
*Este documento foi gerado automaticamente para auxiliar na replicação do estilo visual em outros módulos.*