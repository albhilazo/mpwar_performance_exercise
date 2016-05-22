## Diario de Albert

 1. Se requería "sudo" al añadir un repositorio "apt" para poder instalar PHP 5.6. Ansible dispone del parámetro `become: yes` que lo soluciona.

 2. Al desplegar e inicializar la base de datos, Ansible buscaba el fichero SQL en el directorio `current` antes de que Ansistrano hubiese cambiado los symlinks. Se arregló indicando el punto del flujo de Ansistrano en que se construiría la base de datos con la configuración `ansistrano_before_update_code_tasks_file`.

 3. Se han encontrado complicaciones al habilitar credenciales para Amazon S3. Se han probado diferentes métodos como ficheros de credenciales y variables de entorno, buscando la solución que resultara más fácil para desplegar la aplicación y compartir las configuraciones entre los desarrolladores. Dado que el mismo problema aparecía con otras credenciales y configuraciones de la aplicación, se ha decidido agrupar los parámetros en un único fichero no versionado `resources/config/parameters.php`.


