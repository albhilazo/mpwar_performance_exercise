## Diario de Albert

 1. Se requería "sudo" al añadir un repositorio "apt" para poder instalar PHP 5.6. Ansible dispone del parámetro `become: yes` que lo soluciona.

 2. Al desplegar e inicializar la base de datos, Ansible buscaba el fichero SQL en el directorio `current` antes de que Ansistrano hubiese cambiado los symlinks. Se arregló indicando el punto del flujo de Ansistrano en que se construiría la base de datos con la configuración `ansistrano_before_update_code_tasks_file`.

 3. Se han encontrado problemas al desplegar la aplicación en instancias que habían alojado versiones anteriores del código o antiguas infraestructuras.

 4. Se han encontrado complicaciones al habilitar credenciales para Amazon S3. Se han probado diferentes métodos como ficheros de credenciales y variables de entorno, buscando la solución que resultara más fácil para desplegar la aplicación y compartir las configuraciones entre los desarrolladores. Dado que el mismo problema aparecía con otras credenciales y configuraciones de la aplicación, se ha decidido agrupar los parámetros en un único fichero no versionado `resources/config/parameters.php`.

 5. Tanto para el servicio de S3 como el resto de utilizados en la aplicación se han intentado buscar las mejores restricciones de acceso. Las políticas de modificación y consulta se han establecido procurando un equilibrio entre seguridad y flexibilidad al desplegar la aplicación.

 6. Apareció un problema con las plantillas de Twig, no se trataban ciertos controles de variables como esperábamos y se desencadenaba un error cuando éstas eran arrays vacías o null.

 7. Las instancias EC2 para una de las cuentas de AWS parecían tener un rendimiento muy inferior a las demás, llevando a resultados mucho peores de lo esperado aún con instancias recién creadas. Tras identificar el problema e intentar arreglarlo sin éxito se decide desplegar la aplicación en otra de las cuentas y realizar las pruebas de rendimiento en la misma.
