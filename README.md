# NIR Generator
A Symfony application to generate NIRs (French Registration Number).
Source: [Wikipedia](https://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France#cite_note-sexe-11)

## Usage
### Basic usage
```shell
php bin/console nir:generate
```

### Advanced options

| Option     | Shortcut    | Option value | Description                            |
|------------|-------------|--------------|----------------------------------------|
| --count    | -c          | integer      | Amount of NIRs to generate             |
| --with-key | Not defined | No value     | Generate the last 2 digits control key |
| --raw      | -r          | No value     | Format the NIR without any space       |
