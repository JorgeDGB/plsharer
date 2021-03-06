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

* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

# Instalación del entorno de desarrollo

## Instalación de los paquetes necesarios

Asegúrese de tener instalados los paquetes necesarios para cumplir con las 
condiciones mínimas del entorno de desarrollo. Para ello, ejecute el siguiente 
comando:

    $ sudo aptitude install curl apache2 postgresql php5 libapache2-mod-php5 php5-pgsql php5-intl php-apc

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
    postgres=# ALTER USER plsharer CREATEDB;
    ALTER ROLE
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

En donde dice your_gmail_user, coloque la parte de su correo gmail que va 
antes del \@.

Ahora dale permisos de escritura a las carpetas de cache y log, las cuales 
serán modificadas por apache:

    $ chmod 777 app/cache
    $ chmod 777 app/logs

Por último, crea un link simbólico al script que checkea la configuración para 
ver que todo esté en orden:

    $ cd ~/projects/plsharer
    $ ln -s -T /home/user/projects/plsharer/app/check.php web/check.php

Y checkea la configuración del php del terminal y el php del browser.

Para checkear la configuración del php de la terminar, corre desde la carpeta 
raiz de tu proyecto:

    $ php app/check.php

Revisa los archivos `php.ini` mencionados en el script y corrige lo necesario 
y luego checkea la configuración del php del browser visitando [http://plsharer.com/check.php](http://plsharer.com/check.php) para 
ver el resultado.

En ambas pruebas debes obtener un resultado como éste:

```
********************************
*                              *
*  Symfony requirements check  *
*                              *
********************************

* Configuration file used by PHP: /etc/php5/apache2/php.ini

** ATTENTION **
*  The PHP CLI can use a different php.ini file
*  than the one used with your web server.
*  To be on the safe side, please also launch the requirements check
*  from your web server using the web/config.php script.

** Mandatory requirements **

 OK       PHP version must be at least 5.3.3 (5.4.4-14+deb7u3 installed)
 OK       PHP version must not be 5.3.16 as Symfony won't work properly with it
 OK       Vendor libraries must be installed
 OK       app/cache/ directory must be writable
 OK       app/logs/ directory must be writable
 OK       date.timezone setting must be set
 OK       Configured default timezone "America/Caracas" must be supported by your installation of PHP
 OK       json_encode() must be available
 OK       session_start() must be available
 OK       ctype_alpha() must be available
 OK       token_get_all() must be available
 OK       simplexml_import_dom() must be available
 OK       APC version must be at least 3.1.13 when using PHP 5.4
 OK       detect_unicode must be disabled in php.ini
 OK       xdebug.show_exception_trace must be disabled in php.ini
 OK       xdebug.scream must be disabled in php.ini
 OK       PCRE extension must be available

** Optional recommendations **

 OK       xdebug.max_nesting_level should be above 100 in php.ini
 OK       Requirements file should be up-to-date
 OK       You should use at least PHP 5.3.4 due to PHP bug #52083 in earlier versions
 OK       When using annotations you should have at least PHP 5.3.8 due to PHP bug #55156
 OK       You should not use PHP 5.4.0 due to the PHP bug #61453
 OK       PCRE extension should be at least version 8.0 (8.3 installed)
 OK       PHP-XML module should be installed
 OK       mb_strlen() should be available
 OK       iconv() should be available
 OK       utf8_decode() should be available
 OK       posix_isatty() should be available
 OK       intl extension should be available
 OK       intl extension should be correctly configured
 OK       intl ICU version should be at least 4+
 OK       a PHP accelerator should be installed
 OK       short_open_tag should be disabled in php.ini
 OK       magic_quotes_gpc should be disabled in php.ini
 OK       register_globals should be disabled in php.ini
 OK       session.auto_start should be disabled in php.ini
 OK       PDO should be installed
 OK       PDO should have some drivers installed (currently available: pgsql)
```

Nota que cada prueba usa su propio `php.ini`. El mismo es indicado en el 
principio de la salida del script de checkeo. Asegúrate de editar ambos.

Ahora elimina el link simbólico:

    $ rm web/check.php

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
    artists     : Artist
    genre       : Genre
    tags        : [Tag]

class Song:
    title       : string
    length      : int
    albums      : [Album]
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
    updated_at  : datetime

class Vote:
    caster      : User
    playlist    : Playlist
    stars       : int          {1,2,3,4,5}
    created_at  : datetime

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

### Creando el modelo

Para empezar, usamos la herramienta `console` de la linea de comando de 
Symfony2 para generar los Bundles:

    $ php app/console generate:bundle --namespace=PlSharer/AuthBundle
    $ php app/console generate:bundle --namespace=PlSharer/MusicBundle
    $ php app/console generate:bundle --namespace=PlSharer/RankingBundle
    $ php app/console generate:bundle --namespace=PlSharer/SearchBundle

Una cónsola interactiva se muestra. Acepta todo a excepción del formato, para 
el cual hemos decidido usar `yml`, y cuando te pregunte si deseas que genere 
toda la estructura de directorios, responde `yes`.

Al final se habrá generado la siguiente estructura de directorios:

```
plsharer
 |_app
 |_bin
 |_src
 |  |_PlSharer
 |     |_AuthBundle
 |        |_Controller
 |        |_DependencyInjection
 |        |_Resources
 |        |_Tests
 |     |_MusicBundle
 |        |_Controller
 |        |_DependencyInjection
 |        |_Resources
 |        |_Tests
 |     |_RankingBundle
 |        |_Controller
 |        |_DependencyInjection
 |        |_Resources
 |        |_Tests
 |     |_SearchBundle
 |        |_Controller
 |        |_DependencyInjection
 |        |_Resources
 |        |_Tests
 |_vendor
 |_web
```

Poco a poco usaremos cada uno de los archivos y directorios generados. 

Ahora empezemos a usar las herramientas de cli que proporciona Doctrine. Para 
empezar, creemos la base de datos, por línea de comandos:

    $ php app/console doctrine:database:create

Ahora debemos crear las entidades o _Entities_. Para ello creamos en cada uno 
de los bundles recien creados una carpeta llamada **Entity**

Utiliza el siguiente comando para ejecutar un _wizard_ interactivo que te 
guiará para construir la primera entidad. Haremos primero al entidad User. 
Utiliza un nombre diferente (por ejemplo, MyUser) durante el wizard, porque la 
palabra User es reservada en los DBMS. Luego le haremos un cambio al código 
generado para que pueda llamarse User. Dile que si a la generación del 
repositorio. Esta clase será usada para acceder a conjuntos de usuarios.

    $ mv src/PlSharer/AuthBundle/Entity/MyUser.php src/PlSharer/AuthBundle/Entity/User.php
    $ mv src/PlSharer/AuthBundle/Entity/MyUserRepository.php src/PlSharer/AuthBundle/Entity/UserRepository.php

Ahora cambia los nombres de las clases para que coincidan con los nombres 
nuevos de los archivos.

En el archivo `User.php`, cambia la anotación `@ORM\Table()` por
`@ORM\Table(name="SfUser")`, y ya no habrá problema con la palabra reservada.

Repite el proceso para cada una de las entidades descritas anteriormente. Este 
proceso sin embargo, no te deja crear referencias a otras clases. Crea los 
campos de tipo string, y luego los modificamos.


Vamos ahora a establecer las relaciones entre las clases. Imagina cómo son las 
relaciones entre las clases, según lo definido arriba, y trata de utilizar las 
anotaciones de Doctrine para definir las relaciones `OneToOne`, `ManyToOne`, 
`OneToMany` y `ManyToMany`. En las relaciones bidireccionales, es importante 
elegir correctamente el _owner side_, esto es, la clase que es responsable por 
la relación. Para elegir bien, ayuda pensar que en el formulario del _owner
side_ es común que se deba elegir uno o varios elementos pertenecientes a la 
otra clase de la relación para la creación; esto es, La creación de una 
instancia  de la clase del _owner side_ es la responsable de guardar la 
relación en el momento de su creación. Si dudas, checkea el código para ver 
nuestra aproximación.

Para salir de una vez de la definición de los modelos, en todas las clases que 
tienen campos como `createdAt` o `updatedAt`, vamos a hacer estos campos 
_timestampables_, a través del bundle de Doctrine Extensions de Gedmo. Para 
instalarlo, seguir [estas las instrucciones][6].

Luego de instalarlo, importar en cada clase en que sea necesario el paquete, 
colocando la siguiente línea en la cabezera del archivo:

    ....
    use Gedmo\Mapping\Annotation as Gedmo;
    ....


Luego, anotar cada campo `createdAt` y `updatedAt` como convenga, como en el 
ejemplo que sigue:

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

Para finalizar con el modelo, hay que escribir el comportamiento de los campos 
que representan archivos. Esto es en las clases `User` y `Song`.

En ambas clases se debe importar el validador, que le dirá a symfony que el campo al que fué aplicado manejará la carga de un archivo.

    use Symfony\Component\Validator\Constraints as Assert;

Crea una nueva propiedad para cada clase llamada `path`, que se encargue de 
almacenar la ruta del archivo cargado, y quita las anotaciones de ORM de los campos anteriores. Añade a cada clase los siguientes métodos:

```
    .
    .
    .
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;
    .
    .
    .
    // Mas cosas
    .
    .
    .
    //Al final

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
```

Estos metodos son métodos de conveniecia para saber donde almacenar el archivo 
cargado en el sistema de archivos del servidor. Ahora falta crear la propiedad 
que se encarga de manejar el _objeto_ archivo, o `UploadedFile`. Para ello, en 
`User` utiliza la propiedad `picture`, y en `Song` utiliza la propiedad 
`file`, como sigue:

```
// En User:

    .
    .
    .
    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    private $picture;
    .
    .
    .

// En Song:

    .
    .
    .
    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    .
    .
    .

```

Con esto deberíamos estar listos. A continuación guarda todo y vamos a crear 
hacer que symfony genere todos los accesores la herramienta `app/console`:

    $ php app/console doctrine:generate:entities PlSharer

Nota que se generaron todos los _getters_, _setters_, y que para las clases 
que tienen propiedades multivaluadas (relaciones uno a muchos o mucho a 
muchos), se generó el constructor de la clase, inicializando las propiedades 
correspondientes con el tipo `ArrayCollection` de Doctrine.

Ahora creamos el esquema de datos:

    $ php app/console doctrine:schema:update --force
    Updating database schema...
    Database schema updated successfully! "53" queries were executed

Aprovecha para checkear que no tengas errores de sintaxis. Este comando, luego 
de checkear que todas las declaraciones y aserciones tengan sentido, genera el 
sql necesario para crear todas las tablas que mapean las clases que acabamos 
de escribir. De ahora en adelante sólo tenemos que referirnos a estas clases  
y Doctrine se encargará de persistirlas en la base de datos por nosotros.

### Creando la autenticación

Primero añadimos el campo `salt`, que será usado para complementar la 
contraseña entrada por el usuario y almacenar una contraseña mas fuerte. Luego 
añadimos un poco de validación, usando las anotaciones con `Assert`. Checkea 
el código para que veas las opciones disponibles.

Hagamos al usuario implementar la interfaz `AdvancedUserInterface`, y de la 
interfaz `\Serializable` para que el mecanismo de seguridad de symfony pueda 
hacer su trabajo de autenticación. Para ello, creamos todos los métodos 
necesarios para su implementación:

```
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
```

Luego configuramos los mecanismos de seguridad en el archivo 
`app/config/security.yml`:

```
# app/config/security.yml
security:
    encoders:
        Acme\UserBundle\Entity\User:
            algorithm:        sha1
            encode_as_base64: false
            iterations:       1

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH ]

    providers:
        administrators:
            entity: { class: AcmeUserBundle:User, property: username }

    firewalls:
        admin_area:
            pattern:    ^/admin
            http_basic: ~

    access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }
```



* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

[1]: # "Inicio de la investigación del framework"
[2]: #tutorial-paso-a-paso "Inicio del tutorial"
[3]: http://getcomposer.org/ "Composer"
[4]: # "Estructura de directorios y configuración de Symfony"
[5]: https://github.com/throoze/plsharer "Repositorio de PlSharer"
[6]: https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/symfony2.md "Instalación de gedmo/doctrine-extensions"