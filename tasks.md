# Ticket #456

<!-- Contexto -->

### Tarefa 1: 
Corrigido o comportamento da aplicação para permitir que o campo URL de um condomínio seja opcional, mantendo a consistência com a regra de negócio informada no enunciado. A validação anterior exigia obrigatoriamente a URL, mesmo quando o campo era enviado em branco ou não enviado, o que impedia o cadastro de novos condomínios.
Para isso, foi utilizado uma migration incremental para modificar a estrutura da base de dados sem afetar o histórico de versões.

### Tarefa 2:
Identificado que, embora o banco de dados impeça a duplicação de URLs de condomínios (via constraint `UNIQUE`), ao tentar inserir um valor já existente, o sistema retornava um erro 500 com mensagem técnica do banco com 'vazamento' de alguns dados, esse comportamento não é amigável para o usuário e pode expor informações sensíveis.

O objetivo dessa tarefa foi implementar uma verificação de duplicidade da URL **antes de tentar persistir no banco**, retornando um erro 422 amigável quando necessário, sem remover a validação já existente, garantindo segurança e robustez ao sistema.

<!-- Tarefas -->

<!-- Tarefa 1 -->
### Tarefa 1:
## Correção da obrigatoriedade e validação do campo URL
- [x] Criada a migration incremental `20250430092800_make_url_nullable_on_condominiums`:
  - [x] Tornou o campo `url` da tabela `condominiums` opcional (`nullable`), sem alterar a migration original.

- [x] Atualizado o método `validateCondominiumData` no `CondominiumService`:
  - [x] Aplicado `trim()` ao campo `url` para remover espaços desnecessários.
  - [x] Convertido o campo em `null` caso seja vazio após o `trim`.
  - [x] Utilizado `filter_var` para validar o formato da URL apenas quando ela for informada.
  - [x] Retornado o array tratado e validado para ser usado no `create()` e `update()`.

- [x] Atualizados os métodos `create` e `update`:
  - [x] Passam a receber os dados já tratados e limpos pela validação.
  - [x] `url` agora é enviado corretamente como `null` quando omitido ou enviado em branco.

- [x] Comentários explicativos adicionados no código para facilitar a leitura do revisor.

- [x] Testado via Insomnia:
  - [x] Cadastro sem `url`: ✅ sucesso
  - [x] Cadastro com `"   "` como `url`: ✅ convertido para `null`
  - [x] Cadastro com URL válida: ✅ sucesso
  - [x] Cadastro com URL inválida: ❌ erro 422 com mensagem amigável

<!-- Tarefa 2 -->

###Tarefa 2:
## Validação de unicidade da URL com mensagem clara:
- [x] Criado o método privado `checkUrlUniqueness` no `CondominiumService`:
  - [x] Verifica se já existe outro condomínio com a mesma URL.
  - [x] Ignora a verificação quando o campo `url` é nulo ou vazio.
  - [x] Ignora o próprio condomínio durante atualizações (`update`) para evitar autoconflito.
  - [x] Lança uma `HttpUnprocessableEntityException` com a mensagem amigável `URL is already in use by another condominium.`

- [x] Atualizados os métodos `create()` e `update()`:
  - [x] Chamada da verificação antes de tentar salvar os dados no banco.

- [x] Testado via Insomnia:
  - [x] Criação de condomínio com URL nova → ✅ sucesso
  - [x] Criação com URL já usada por outro condomínio → ❌ erro 422 amigável
  - [x] Atualização sem alterar a URL → ✅ sucesso
  - [x] Atualização tentando usar URL de outro condomínio → ❌ erro 422 amigável
  - [x] Criação/atualização com URL vazia ou nula → ✅ sucesso (ignorado)

<!-- Tarefa 3 -->