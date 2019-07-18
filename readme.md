## Me Ajuda 2 - Sistema Financeiro

Criado com a proposta de ajudar pessoas leigas.

### Projeto

- [Laravel 5.6](https://laravel.com/docs/5.6)
- MySQL 5.7
- PHP 7.2.19

#### DB SQL

```sql
--
-- Estrutura da tabela `lancamentos`
--

CREATE TABLE `lancamentos` (
  `id` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `conta` varchar(255) NOT NULL,
  `confirmacao_pagamento` varchar(255) NOT NULL,
  `valor` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

```sql

--
-- Estrutura da tabela `contas`
--

CREATE TABLE `contas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

```
#### Live Demo: [Aqui](http://165.227.94.2/)