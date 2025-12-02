# Sistema de Credenciamento TCC - Documentação

## 📋 Visão Geral
Sistema simplificado de credenciamento e controle de entrada para eventos, desenvolvido para TCC.

## 🏗️ Arquitetura

### Estrutura MVC
- **Models**: Lógica de negócio e acesso a dados
- **Views**: Interface do usuário
- **Controllers**: Controle de fluxo e rotas
- **Services**: Serviços reutilizáveis

## 🔐 Sistema de Permissões

### Níveis de Acesso
O sistema possui 3 níveis de permissão:

#### 1. **SUPERADMIN** (ID: 1)
- Acesso total ao sistema
- Gerencia todos os eventos
- Configura sistema e usuários
- Acesso a todas as funcionalidades

#### 2. **ADMIN** (ID: 2) 
- Administrador de evento específico
- Gerencia apenas seu evento vinculado
- Acesso a credenciamento do seu evento
- Não pode alterar configurações do sistema

#### 3. **USUARIO** (ID: 3)
- Operador do sistema
- Realiza credenciamento
- Acesso básico às funcionalidades
- Não pode alterar configurações

### Usuários Padrão

|    Tipo    |    Login    |  Senha   |      Descrição      |        Email         |
|------------|-------------|----------|---------------------|----------------------|
| SUPERADMIN | super.admin | Admin123 | Administrador total | superadmin@gmail.com |
| ADMIN      | admin       | Admin123 | Admin               | admin@gmail.com      |
| USUARIO    | usuario     | Admin123 | Operador comum      | usuario@gmail.com    |

## 🗄️ Banco de Dados

### Tabelas Principais

#### Pessoas
- `tblPessoa` - Dados pessoais básicos
- `tblDocumento` - Documentos (CPF, RG, etc)
- `tblTelefone` - Telefones
- `tblEmail` - E-mails
- `tblEndereco` - Endereços

#### Eventos
- `tblEvento` - Eventos cadastrados
- `tblEventoCategoria` - Categorias de eventos
- `tblLocal` - Locais dos eventos

#### Credenciamento
- `tblCredencial` - Credenciais emitidas
- `tblLote` - Lotes de credenciais
- `tblCredencialPeriodo` - Períodos de acesso

#### Controle
- `tblEntradas` - Log de entradas/saídas
- `tblSetor` - Setores do evento
- `tblTerminal` - Terminais de acesso
- `tblLeitor` - Leitores de credencial

#### Sistema
- `tblUsuario` - Usuários do sistema
- `tblPermissao` - Permissões disponíveis

### Views
- `vwLogin` - Dados de login com permissões
- `vwUsuarios` - Lista de usuários
- `vwEventos` - Eventos com informações completas


## 📁 Estrutura de Pastas

```
tcc/
├── controllers/         # Controladores
├── core/               # Core do sistema
│   ├── controllers/    # Controllers base
│   ├── database/       # Conexão DB
│   ├── models/         # Models base
│   └── utils/          # Utilitários
├── models/             # Modelos de dados
├── services/           # Serviços
├── views/              # Views
├── midia/              # Upload de arquivos
│   ├── pessoas/        # Fotos de pessoas
│   └── eventos/        # Imagens de eventos
├── logs/               # Logs do sistema
└── temp/               # Arquivos temporários
```

## 🚀 Fluxo de Credenciamento

### 1. Busca de Participante
- Busca por CPF ou Passaporte
- Verifica se já existe cadastro
- Verifica se já tem credencial no evento

### 2. Cadastro (se necessário)
- Nome e Sobrenome
- CPF (obrigatório)
- Telefone e E-mail (opcionais)
- Cadastro rápido e simplificado

### 3. Atribuição de Credencial
- Captura de foto (com detecção facial)
- Seleção do lote de credencial
- Geração automática do código
- Salvamento da credencial

### 4. Troca de Credencial
- Para participantes já credenciados
- Desativa credencial anterior
- Cria nova credencial

## 🛠️ Funcionalidades Principais

### Dashboard
- Visão geral do sistema
- Estatísticas de credenciamento
- Eventos ativos

### Eventos
- Cadastro e edição
- Componentes do evento
- Configuração de locais
- Categorias

### Credenciamento
- Busca de participantes
- Cadastro rápido
- Emissão de credenciais
- Captura de foto com IA

### Recursos
- Setores do evento
- Terminais de acesso
- Leitores de credencial

### Lotes
- Criação de lotes
- Tipos de credencial
- Controle de quantidade
- Períodos de acesso

## 📊 Relatórios

- Total de credenciados
- Credenciais por lote
- Entradas e saídas
- Participantes por setor

## 🔍 Logs

Sistema completo de logs em `logs/`:
- Erros e exceções
- Acessos ao sistema
- Operações de credenciamento
- Alterações de dados

## 🌐 APIs Internas

### /credenciamento/buscaDoc/{documento}/{evento}
Busca participante por documento

### /credenciamento/cadastrarPessoa
Cadastra novo participante

### /credenciamento/lotesDisponiveis
Lista lotes disponíveis para credenciamento

### /credenciamento/salvar
Salva ou atualiza credencial

## 📱 Responsividade

Interface responsiva usando:
- AdminLTE 3
- Bootstrap 4
- jQuery
- Select2
- SweetAlert2

## 🔒 Segurança

- Senhas criptografadas (bcrypt)
- Validação de sessão
- Proteção CSRF
- Logs de auditoria
- Controle de acesso por permissão

## 📈 Performance

- Conexão única ao banco (Singleton)
- Views otimizadas
- Cache de sessão
- Lazy loading de serviços

## 🐛 Troubleshooting

### Erro de Login
- Verificar credenciais
- Verificar status do usuário
- Checar logs em `logs/`

### Erro de Permissão
- Verificar nível de acesso
- Confirmar vínculo com evento (para ADMIN)

### Erro de Upload
- Verificar permissões da pasta `midia/`
- Verificar limite de upload do PHP

### Erro de Banco
- Verificar conexão em `config.php`
- Executar script SQL de ajuste de permissões
- Verificar estrutura das tabelas

## 📝 Notas de Desenvolvimento

1. Sistema simplificado sem multi-empresa
2. Foco no fluxo de credenciamento
3. Permissões em 3 níveis apenas
4. Detecção facial via face-api.js (CDN)
5. Upload de fotos em base64

---

**Desenvolvido para TCC 2025**
Sistema de Credenciamento Simplificado
