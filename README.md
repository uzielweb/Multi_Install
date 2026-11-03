# Multi Install para Joomla 6

Extensão para Joomla 6 que permite instalar múltiplos pacotes de extensões (componentes, módulos, plugins, templates) simultaneamente a partir de um único arquivo ZIP ou de uma URL remota.

## Características

- Compatibilidade total com **Joomla 6** e **PHP 8.3 / 8.4**.
- Arquitetura nativa moderna:
  - Injeção de dependências com **Service Provider** (`services/provider.php`).
  - Namespaces PSR-4 (`Uzielweb\Component\Multiinstall`).
  - Web Asset Manager (WAM) com `joomla.asset.json`.
  - Formulários e views nativas com tokens CSRF e validação.
  - Padrão MVC estrito (Controller, Model, View, Tmpl).
- Suporte a internacionalização com paridade integral: **Português (pt-BR)** e **Inglês (en-GB)**.
- Interface moderna integrada ao template de administração do Joomla (Atum).

## Instalação

1. Compacte o conteúdo do repositório em um arquivo `.zip` ou baixe o pacote gerado na aba de lançamentos.
2. Acesse o Painel de Controle do Joomla: **Sistema > Instalar > Extensões**.
3. Envie o pacote ZIP.
4. Acesse o componente pelo menu: **Componentes > Multi Instalador**.

## Licença

GNU General Public License version 2 or later; see LICENSE.txt.