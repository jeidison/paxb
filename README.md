# PAXB (PHP Architecture for XML Binding)

## Intrudução
Esse projeto é inspirado no [JAXB](https://docs.oracle.com/javase/tutorial/jaxb/intro/index.html) da linguagem Java.

O PAXB fornece uma maneira rápida e conveniente de empacotar (gravar) objetos PHP em XML e descompactar (ler) XML em objetos.
Ele suporta uma estrutura de ligação que mapeia elementos e atributos XML para propriedades PHP usando [atributos no PHP 8](https://www.php.net/manual/pt_BR/language.attributes.php). 

## Requisitos
* PHP 8.0+
* A extensão dom

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
indicar ao PAXB como gerenciar um tipo específico.

## Exemplo de Adaptador
```php
<?php
...

class DateBrAdapter implements XmlAdapter
{

    public function marshal(object $object): ?string
    {
        return $object->format('d/m/Y');
    }

    public function unmarshal(object $object): ?object
    {
        return $object
    }
}
```

## Exemplo de uso

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

## Autor
- [Jeidison Farias](https://github.com/jeidison)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.