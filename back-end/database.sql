-- Criando database
create database burguer_crud;

-- Criando as tabelas
create table membros(
-- serial => começa com o valor 1 e a cada inclusão, o valor aumenta +1
codmembro serial primary key,
nome varchar(50) not null,
sobrenome varchar(50) not null,
cidade varchar(50),
estado varchar(2),
email varchar(50),
usuario varchar(50) not null,
senha varchar(50) not null,
dataCriacao date not null,
tipoPermissao boolean);

create table receitas(
codreceita serial primary key,
titulo varchar(50) not null,
descricao text not null,
likes int,
tipoCarne boolean not null,
avaliacao float,
membros_codmembro int not null,
-- foreign key => Define a chave estrangeira
foreign key(membros_codmembro) references membros(codmembro));

create table comentarios(
codcomentarios serial primary key,
comentario text not null,
dataCriacao date not null,
likes int,
receitas_codreceita int not null,
membros_codmembro int not null,
foreign key(receitas_codreceita) references receitas(codreceita),
foreign key(membros_codmembro) references membros(codmembro));