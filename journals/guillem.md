Guillem Pascual Log
=================
----------

Problemas
--------------
- se añaden librerías js para compilar css
- - catwc no funciona como se espera y se desecha
- - problemas con el formato de comandos para compilar css en package.json, se da con el adecuado
- - se intenta usar uglifyjs para minificar css erróneamente, se decide usar css-clean
- - se realiza el comando build en package.json para compilar css y minificarlo al mismo tiempo
- cuando intentas hacer un git push sube todo el directorio node-modules dentro de bootstrap aunque esté en .gitignore, parece que no tiene en cuenta la regla si ya se ha hecho un commit previamente
- se corrige <head> que faltaba en todas las templates
- decisión de cachear queries por artículo en redis:
- - no refrescaba los resultados cuando se hacían cambios. Se cambió el método *save* para que borrara la cache redis de artículo modificado.
- - se tuvo que modificar las clases en Database que consultaban el repositorio de artículos con Doctrine, porque a *persist()* no le gustaba como le pasaba el objeto *article* el decorador *ArticleRepositoryCached.php*   (aunque en principio era transparente)
- - cuando se editaba un artículo, el cambio no se veía reflejado en la *home* que lista todos los artículos. Se cambió el método *save* para que borrara la cache redis del listado de artículos
- problema con twig, se añadió detección de que objeto *article* en la parte de rankings de *home*
- problema con refresco de caches, el método *save* de *ArticleRepositoryCached.php* confundía nombre de la variable *$key* aunque en principio pertenecía a ámbitos diferentes y no debería.


- cuando se despliega la app a los dos servidores con sudo ansible-playbook --private-key /var/www/mpwar16-lasalle.pem -u ubuntu -i 54.186.96.67,54.186.40.71, deploy.yml, solo despliega en la segunda IP y no en la primera

- se solventa cuando se traslada la pem a otro directorio y se cambia los permisos de la pem a 600
- nos damos cuenta de que en infrastructure.yml aún está la instrucción de instalar MySQL, que es la que da problemas. Pablo la elimina.
- aún así, falla
- nos damos cuenta que el problema puede venir por reaprovechar una de las instancias EC2 que tenía un demo de Symfony, al instalar PHP, daba error.
- Se elimina la instancia, se crea una nueva y se despliega.
sudo ansible-playbook --private-key /home/vagrant/mpwar16-lasalle.pem -u ubuntu -i 54.186.88.140,54.186.40.71, deploy.yml

- Ya funciona
- Se añade la línea en parameters.php para que coja las imágenes de CloudFront y los css también

- Problemas midiendo los tiempos. Al comprobar los tiempos del los hitos, lo verificamos en instancias EC2 diferentes para hitos diferentes, (Hitos 1-3 en EC2 de Pablo, hitos 4-6 en EC2 de Guillem), y arrojaban resultados con un orden de magnitud de diferencia. Al parecer, las máquinas EC2 que Amazon provisionaba en mi caso tenían muy bajo rendiemiento, aunque eran del mismo tipo que las de Pablo (Ubuntu 14 64 bits)
Se decide repetir los hitos 4, 5, y 6 en las instancias EC2 de Pablo en lugar de las de Guillem.
