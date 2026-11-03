# Multi Install para Joomla 6

Extensão para Joomla 6 que permite instalar múltiplos pacotes de extensões (componentes, módulos, plugins, templates) simultaneamente a partir de um único arquivo ZIP ou de uma URL remota.

## Autoria e Manutenção

- **Mantenedor & Desenvolvedor:** Uziel Almeida Oliveira ([Ponto Mega](https://pontomega.com.br))
- **Autor Original:** Chris Taylor (versão 1.0 legado)
- **Repositório:** [https://github.com/uzielweb/Multi_Install](https://github.com/uzielweb/Multi_Install)

## Características da Versão 2.0.0

- Compatibilidade total com **Joomla 6** e **PHP 8.3 / 8.4**.
- Suporte nativo ao **Joomla Update Server** (`update.xml`) integrado ao Painel de Controle.
- Arquitetura nativa moderna:
  - Injeção de dependências com **Service Provider** (`services/provider.php`).
  - Namespaces PSR-4 (`Uzielweb\Component\Multiinstall`).
  - Extração resiliente com `Joomla\Archive` (suporte com e sem extensão `zip` nativa).
  - Web Asset Manager (WAM) com `joomla.asset.json`.
  - Formulários e views nativas com tokens CSRF e validação.
  - Padrão MVC estrito com controle de permissões ACL (`access.xml` e `config.xml`).
- Suporte a internacionalização com paridade integral: **Português (pt-BR)** e **Inglês (en-GB)**.
- Interface moderna integrada ao template de administração do Joomla (Atum / Bootstrap 5).

## Instalação

1. Baixe o pacote de lançamento `com_multiinstall-v2.0.0.zip` na aba de [Releases](https://github.com/uzielweb/Multi_Install/releases).
2. Acesse o Painel de Controle do Joomla: **Sistema > Instalar > Extensões**.
3. Envie o pacote ZIP.
4. Acesse o componente pelo menu: **Componentes > Multi Instalador**.

## Licença

GNU General Public License version 2 or later; see LICENSE.txt.