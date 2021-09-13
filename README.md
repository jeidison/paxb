# PAXB (PHP Architecture for XML Binding)

## Intrudução
O PAXB fornece uma maneira rápida e conveniente de gerar XML a partir de objetos PHP e ler XML em objetos.
Ele suporta uma estrutura de ligação que mapeia elementos e atributos XML para propriedades PHP usando [atributos no PHP 8](https://www.php.net/manual/pt_BR/language.attributes.php). 

## Requisitos
* PHP 8.0+
* Extensão dom

## Instalação

```bash
$ composer require jeidison/paxb
```

## Atributos suportados

- \#[XmlAttribute]
- \#[XmlElement]
- \#[XmlRootElement]
- \#[XmlTransient]
- \#[XmlType]
- \#[XmlPhpTypeAdapter]

## Adaptadores

Ao lidar com tipos que precisam de modificações nos valores antes de gerar o XML, podemos escrever um adaptador para 
indicar ao PAXB como gerenciar o valor de um tipo específico.

## Exemplo de adaptador
```php
<?php
...

class DateBrAdapter implements XmlAdapter
{
    public function marshal(?object $object): string
    {
        return $object->format('d/m/Y');
    }

    public function unmarshal(?string $object): ?object
    {
        return DateTime::createFromFormat('d/m/Y', $object)
                       ->setTime(null, null, null);
    }
}
```

## Exemplo de uso dos atributos

```php
<?php
...

#[XmlRootElement("livros")]
class Book
{
    #[XmlAttribute("identificador")]
    private int $id;

    #[XmlElement("nome")]
    private String $name;

    #[XmlPhpTypeAdapter(DateBrAdapter::class)]
    private DateTime $date;

    #[XmlElement("author_data")]
    private Author $author;

    #[XmlTransient]
    private string $address;
    
    ...
```

## Gerando XML
```php
<?php
...

$book = new Book();
$book->setId(1);
$book->setName("PHP XML Binding");
$book->setAuthor($author);
$book->setDate(new DateTime());
$book->setAddress($address)

$paxb = PAXB::createMarshaller();
$xml  = $paxb->marshal($book);

echo $xml;
...
```

## Exemplo de XML gerado

```xml
<?xml version="1.0" encoding="UTF-8"?>
<livros identificador="1">
    <nome>PHP XML Binding</nome>
    <date>10/09/2021</date>
    <author_data>
        <name>Jeidison Farias</name>
        <address>
            <number>123</number>
            <street>Rua 10</street>
        </address>
    </author_data>
</livros>
```

## Transformando XML em objetos PHP

```php
<?php
...

$xml = 'O seu XML';

$unmarshaller = PAXB::createUnmarshal(Book::class);
$book = $unmarshaller->unmarshal($xml);

```
<hr>

Esse projeto é inspirado no [JAXB](https://docs.oracle.com/javase/tutorial/jaxb/intro/index.html) da linguagem Java.

<hr>

## Autor
- [Jeidison Farias](https://github.com/jeidison)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.