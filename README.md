PlSharer
====================================================

*Proyecto 2 -- Research and Write your Own Tutorial*

*Herramientas de Programación en Internet (CI-5644)*


El siguiente proyecto tiene por finalidad mostrar las características del 
framework de desarrollo web 'Symfony2'. En el mismo, podrá encontrar lo 
siguiente:

* [Una investigación del framework][1]       
* Una aplicación simple escrita usando el framework Symfony2
* Documentación paso a paso (como un [tutorial][2]) de el desarrollo de la
  aplicación.

Puede encontrar el README oficial que trae Symfony2 en el archivo
README.oficial.md

Tutorial Paso a Paso
--------------------

El siguiente tutorial fué desarrollado en un entorno con las siguientes
características:

**Sistema Operativo:**

    Debian 7 Wheezy

**Arquitectura:**

    amd64

**Versión de PHP:**

    PHP 5.4.4-14+deb7u2 (cli) (built: Jun  5 2013 07:56:44) 
    Copyright (c) 1997-2012 The PHP Group
    Zend Engine v2.4.0, Copyright (c) 1998-2012 Zend Technologies
        with Xdebug v2.2.1, Copyright (c) 2002-2012, by Derick Rethans

**Versión de Apache:**

    2.2.22-13

**Versión de PostgreSQL:**

    psql (PostgreSQL) 9.1.9

**Versión de Symfony2:**

    Symfony Standard 2.3.2

**Versión de GIT:**
    
    1.7.10.4


> NOTA:
> 
> Las siguientes instrucciones asumen que se trabaja en un entorno similar, y
> requieren al como mínimo una estación de trabajo con un sistema operativo
> Debian-like y una conexión a internet. Se asume también que se dispone de el
> usuario 'user', el cual debe ser `sudoer`.

*******************************************************************************

# Instalación del entorno de desarrollo

## Instalación de los paquetes necesarios

Asegúrese de tener instalados los paquetes necesarios para cumplir con las 
condiciones mínimas del entorno de desarrollo. Para ello, ejecute el siguiente 
comando:

    $ sudo aptitude install curl apache2 postgresql php5 libapache2-mod-php5 php5-pgsql

> Nota:
> 
> En los comandos anteriores, y en lo sucesivo, se usa el caracter `$` al 
> inicio del comando para denotar que se está utilizando un usuario del 
> sistema operativo sin privilegios, y el caractér `#` para denotar que se 
> está usando el usuario `root`, el cual tiene privilegios de superusuario.

## Configuración de PostgreSQL

En un principio, postgres no deja entrar como un usuario normal a la consola.
Para lograrlo, ejecute los siguientes pasos:

Cree un usuario y su base de datos para el proyecto:

    $ sudo su
    # su postgres
    $ psql
    postgres=# CREATE USER plsharer WITH PASSWORD 'your_secret_password';
    CREATE ROLE
    postgres=# CREATE DATABASE plsharer;
    CREATE DATABASE
    postgres=# GRANT ALL PRIVILEGES ON DATABASE plsharer TO plsharer;
    GRANT
    postgres=#\q
    $ exit

> NOTA:
> 
> Sustituya 'your_secret_password' por la contraseña que usted prefiera.

Luego, para permitir que cualquier usuario pueda establecer una conexión al
servidor de postgres, edite los archivos de configuración de postgres:

    $ sudo emacs /etc/postgresql/9.1/main/pg_hba.conf &

En ese archivo encontrará primero una explicación de cómo se configura, y al 
final, las líneas de nuestro interés. ubique una línea que dice lo siguiente:

    ...
    local   all             all                                     peer
    ...

Coméntela con un `#` por delante, y cópiela abajo, modificada como sigue:

    ...
    #local   all             all                                     peer
    local   all             all                                     md5
    ...

Salve y cierre el archivo. Ahora reinicie el servidor de postgres para que la
nueva configuración tenga efecto:

    $ sudo service postgresql restart
    [ ok ] Restarting PostgreSQL 9.1 database server: main.

Listo, ha terminado con la configuración  de PostgreSQL

## Configuración del servidor Web

En este proyecto usaremos apache como nuestro servidor web. PHP, y 
particularmente Sümfony2, proveen un servidor web apropiado para la etapa de
desarrollo, pero explicaremos de una vez cómo configurar el servidor Apache2 
para desplegar la aplicación. En caso de querer usar el servidor de 
desarrollo, puede saltarse esta parte y usarlo, pero todos los ejemplos 
asumirán que se usa apache.

La mejor manera de alojar varios proyectos en un mismo servidor web, es a 
través de virtual hosts. A continuación configuraremos uno para nuestro 
proyecto.

Primero, crearemos la carpeta que usará el servidor para llegar a su proyecto. 
Usaremos luego links simbólicos a su proyecto, para que al final usted decida 
dónde almacenarlo, y pueda cambiar la ubicación sin tener que hacer cambios en 
la configuración:

    $ sudo mkdir -p /srv/www/plsharer.com/logs
    $ cd /srv/www/plsharer.com
    $ sudo ln -s -T /home/user/projects/plsharer/web public_html

> NOTA:
> 
> Recuerde sustituir `user` por el nombre de su usuario.

Luego configuraremos un virtualhost para que apache sepa que debe servir la 
aplicación desde la dirección que creamos:

    $ cd /etc/apache2/sites-available
    $ sudo touch plsharer.com
    $ sudo emacs plsharer.com

En el archivo abierto, copiar lo siguiente:

    <VirtualHost *:80>
       ServerAdmin admin@plsharer.com
       DocumentRoot /srv/www/plsharer.com/public_html/
       ServerName plsharer.com
       ServerAlias *.plsharer.com
       ErrorLog /srv/www/plsharer.com/logs/error.log
       CustomLog /srv/www/plsharer.com/logs/access.log combined
       #CustomLog /srv/www/plsharer.com/logs/access.log common
       DirectoryIndex app_dev.php
       <Directory "/srv/www/plsharer.com/public_html/">
            Options Indexes FollowSymLinks
            Order Allow,Deny
            Allow from all
            AllowOverride all
            <IfModule mod_php5.c>
               php_admin_flag engine on
               php_admin_flag safe_mode off
               php_admin_value open_basedir none
            </ifModule>
       </Directory>
    </VirtualHost>

Salve el archivo, y a continuación active el nuevo virtualhost:

    $ sudo a2ensite plsharer.com
    $ sudo service apache2 restart

Luego, editar el archivo `hosts` de su sistema para poder hacer los requests a 
la dirección `plsharer.com` o `www.plsharer.com` y que se dirija al
`localhost`:

    $ sudo emacs /etc/hosts

En el archivo abierto, añada una línea al final con lo siguiente:

    ...
    127.0.0.1       www.plsharer.com        plsharer.com

Salve y cierre el archivo. Con esto termina la configuración necesaria de 
apache 2 para desplegar el proyecto.

> NOTA:
> 
> Como debe haber notado, se creó la carpeta `/srv/www/plsharer.com/logs`, y se
> configuró apache para colocar los logs en esa carpeta. Serán dos archivos: 
> `access.log`, donde se guardarán los logs de acceso a su aplicación, y
> `error.log`, donde se registrarán los errores y warnings de su aplicación. 
> Es útil tener esto en mente, para el _debugging_ que eventualmente tendrá 
> que hacer.

## Instalación de Symfony2, e inicialización del proyecto

Vaya a su directorio `$HOME` (asumimos `/home/user/`), y cree la carpeta
projects:

    $ mkdir ~/projects
    $ cd ~/projects

La instalación de Symfony2 recomendada por la documentación oficial es a través
de [Composer][3]. Para realizarla, ejecute los siguientes comandos:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar create-project symfony/framework-standard-edition plsharer

El último comando ejecutará un script que instalará la última versión estable
de Symfony2 en la carpeta `plsharer`. Además, preparará la configuración de su 
proyecto, mostrándole una salida interactiva como sigue:

    Creating the "app/config/parameters.yml" file.
    Some parameters are missing. Please provide them.
    database_driver (pdo_mysql):pdo_pgsql
    database_host (127.0.0.1):
    database_port (null):5432
    database_name (symfony):plsharer
    database_user (root):plsharer
    database_password (null):your_secret_password
    mailer_transport (smtp):
    mailer_host (127.0.0.1):smtp.gmail.com
    mailer_user (null):your_gmail_user
    mailer_password (null):your_gmail_password
    locale (en):
    secret (ThisTokenIsNotSoSecretChangeIt):<a_large_token_secret_enough>

> NOTE:
> 
> En este tutorial utilizaremos el manejador de bases de datos PostgreSQL, 
> para lo cual es necesario el driver `pdo_pgsql`. Ud es libre de usar 
> cualquier otro, pero este tutorial asume que ud utiliza PostgreSQL como su 
> DBMS. Los drivers disponibles son: pdo_mysql, pdo_sqlite, pdo_pgsql,
> pdo_oci, oci8, ibm_db2, pdo_ibm, pdo_sqlsrv, mysqli, drizzle_pdo_mysql,
> sqlsrv.

En donde dice your_gmail_user, coloque la parte de su correo gmail que va antes del \@.

¡Listo! ya está instalado y configurado Symfony2. Para información acerca de 
la estructura de directorios generada y la configuración de Symfony2 haga clik 
[aqui][4].

> Opcional:
> 
> Este proyecto está siendo llevado en un repositorio en [github][5]. Se
> recomienda hacerlo y al menos llevar un repositorio local como buena
> práctica. Para inicializar un repositorio de git local, utilize el comando:
> `$ git init`, situado en la carpeta raiz de su proyecto, y periódicamente
> repita lo siguiente:
>
>     $ git add .
>     $ git commit
>
> Se abrirá un editor. Escriba los avances desde la última vez que repitió
> este proceso, guarde y cierre el editor.

A continuación seguimos con el desarrollo de la aplicación de ejemplo.

# La Aplicación - PlSharer

En este tutorial intentaremos hacer una aplicación mediante la cual los 
usuarios puedan construir listas de música (_playlists_), clasificarlas 
mediante _tags_, publicarlas y compartirlas. Además, el usuario debe poder 
buscar _playlists_ por el texto contenido en el título de alguna de sus 
canciones, o por alguno de los _tags_, que bien pueden ser géneros musicales, 
estados de ánimo, o situaciones en las cuales se desee escuchar esa música (
por ejemplo, `un viaje a la playa`). Un usuario también podrá buscar sólo 
canciones, independientemente del _playlist_ al que pertenezcan. Tanto las 
canciones como los _playlists_ tendrán _tags_ asociados. Los usuarios podrán 
votar por los _playlists_ que consigan, lo que asociará una calificación a 
cada _playlist_, que permitirá _rankear_ las _playlists_ dado un _tag_ en 
particular.

Para representar este problema, diseñamos el siguiente esquema de datos, escrito en pseudolenguaje:

```
class User:
    username    : string
    email       : string
    password    : string
    name        : string
    gender      : char         {"Male":'M', "Female":'F',"Unknown":'U'}
    bio         : longtext
    picture     : file
    created_at  : datetime
    updated_at  : datetime
    is_active   : boolean
    is_admin    : boolean

class Artist:
    name        : string

class Genre:
    name        : string

class Tag:
    name        : string

class Album:
    title       : string
    artists     : [Artist]
    genre       : Genre
    tag         : Tag

class Song:
    title       : string
    length      : time
    album       : Album
    genre       : Genre
    year        : date
    tags        : [Tag]
    file        : file
    youtube     : string
    grooveshark : string

class Playlist:
    title       : string
    author      : User
    songs       : [Song]
    tags        : [Tag]
    rating      : float
    created_at  : datetime

class Vote:
    caster      : User
    playlist    : Playlist
    stars       : int          {1,2,3,4,5}

```

Nótese que la propiedad `rating` de un Playlist es redundante, puesto que el 
rating puede calcularse promediando las estrellas de todos los votos de un 
playlist, pero lo dejaremos explícito y lo calcularemos de nuevo con cada 
nuevo voto para facilitar las búsquedas ordenadas.

Por otro lado, necesitamos diseñar la arquitectura de nuestra aplicación, 
siguiendo la filosofía de Symfony2 de separar la aplicación en _Bundles_. 
Decidimos que todo lo encargado de la autenticación y el manejo de usuarios 
irá en `AuthBundle`, todo lo referente a la información de las canciones y los 
playlists en `MusicBundle`, lo referente al rating y ranking de los playlists 
en 'RankingBundle', y lo referente a la búsqueda y clasificación de las 
canciones en `SearchBundle`.

Si repartimos las clases entre los bundles, tenemos:

```
AuthBundle:
    User

MusicBundle:
    Artist
    Genre
    Album
    Song
    Playlist

RankingBundle:
    Vote

SearchBundle:
    Tag
```

El ORM que usaremos es _Doctrine_. Doctrine necesita que definamos lo que el 
llama _Entities_ o entidades, que corresponderían a cada una de las clases que 
definimos arriba en pseudolenguaje. Dadas ciertas anotaciones sobre las 
propiedades de las entidades (Clases de PHP), Doctrine se encargará de 
mapearlas a tablas en la base de datos.

Ya nos toca empezar a echar código en Symfony2. Para ello, hacemos lo 
recomendado por la documentación: Eliminar el Bundle de demostración 
`AcmeDemoBundle`:

1. Eliminamos la carpeta `src/Acme`.
        $ cd ~/projects/plsharer
        $ rm -rf src/Acme
2. Eliminamos la ruta que referencia `AcmeDemoBundle` en el archivo 
`app/config/routing_dev.yml`.
3. Eliminar `AcmeDemoBundle` de los bundles registrados en `app/AppKernel.php`.
4. Eliminar el directorio `web/bundles/acmedemo`.
5. Eliminar las entradas `security.providers`, `security.firewalls.login` y 
`security.firewalls.secured_area` en el archivo `security.yml` o modifica la 
configuración de seguridad a tu conveniencia.

# Listo para empezar a echar código

Para empezar, usamos la herramienta `console` de la linea de comando de 
Symfony2 para generar los Bundles:

    $ 

<--! Referencias: -->

[1]: # "Inicio de la investigación del framework"
[2]: #tutorial-paso-a-paso "Inicio del tutorial"
[3]: http://getcomposer.org/ "Composer"
[4]: # "Estructura de directorios y configuración de Symfony"
[5]: https://github.com/throoze/plsharer "Repositorio de PlSharer"