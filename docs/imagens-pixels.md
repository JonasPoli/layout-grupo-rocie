

### 🖼️ GERAÇÃO DE IMAGENS DE PLACEHOLDER E INTEGRAÇÃO

As imagens utilizadas no wireframe foram implementadas através de buscas focadas e downloads automatizados consumindo a **API do Pexels** (previamente operando de forma temporária via API aleatória do Lorem Picsum) para garantir hiper-realismo e adequação contextual máxima de um wireframe de alta-fidelidade.

API Key: qndPeVGq3jBQ1nStwERWu10Wp2HIOBpB95ACJhj2QmiCMyky6VNFvfHQ

**Processo de Aquisição:**
1. **Script Automatizado (Python):** Uma rotina em Python foi escrita e executada via terminal solicitando os termos exatos de cada seção predefinida nos blocos em arquivos PHP do layout.
2. **Prompts de Busca Utilizados:**
   - Para o Banner (`hero-bg.jpg`): *"agriculture farm dark green"* (Resolução em Landscape Ultra-HD)
   - Guia do Evento (`flights.jpg` e `hotels.jpg`): *"commercial airplane flying sky"* e *"luxury modern hotel room"*
   - Painéis de Especialistas (`speaker1.jpg`, `speaker2.jpg`, etc.): *"female/male professional smiling portrait"* (Resolução estrita de Portrait com corte).
   - Plenária (`conference-room.jpg`): *"conference presentation hall"*
3. **Download Nativo e Limpeza:** O script percorreu e tratou a resposta JSON capturando o link de mais alta performance de exibição para a devida aplicação sob a classe `object-cover`. Cada binário salvo sob a pasta `/assets/images/` sobrescreveu placeholders neutros. Após os trâmites, o script final foi expurgado para evitar exposição e vazamentos da API Key Pexels (passada como bearer token) para o repositório GIT.