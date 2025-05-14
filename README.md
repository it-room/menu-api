# Application de gestion de menu

## APIs

### Login : /api/login 

<pre>
{
    "username": "alepoutre@itroom.fr",
    "password": "coucou"
}
</pre>

En retour, on a un token jwt

### Liste de mes menus : api/menu/my

On passe le token et en retour on a un json 
<pre    >
    {
        "id": 1,
        "titre": "nouveau Menu",
        "ingrediants": [
            {
                "id": 1,
                "titre": "yaourt"
            },
            {
                "id": 2,
                "titre": "bananes"
            },
            {
                "id": 3,
                "titre": "jus d'orange"
            }
        ],
        "photo": "Capture-d-ecran-2025-04-28-a-09-41-07-681b6ba6c6ef9.png"
    },
    {
        "id": 3,
        "titre": "palmier",
        "ingrediants": [],
        "photo": "100-0488-68243b5f9241f.jpg"
    }

</pre>

### ajoute d'un ou plusieurs ingrédents 
api/menu/{menuId}/ingredients/titles   

<pre>
{
   "titles": ["yaourt", "bananes", "jus d'orange"]
}
</pre>




  api_mes_menu                      GET        ANY      ANY    /api/mesMenu                                   
  api_menu_add_ingredients          POST       ANY      ANY    /api/menu/{menuId}/ingredients                 
  api_menu_add_ingredients_titles   POST       ANY      ANY    /api/menu/{menuId}/ingredients/titles          
  api_menu_update_ingredients       PUT        ANY      ANY    /api/menu/{menuId}/ingredients                 
  api_menu_remove_ingredient        DELETE     ANY      ANY    /api/menu/{menuId}/ingredients/{ingredientId}  
  api_menu_add                      POST       ANY      ANY    /api/menu/add                                  
  api_menu_update                   PUT        ANY      ANY    /api/menu/update/{id}                          
  api_menu_delete                   DELETE     ANY      ANY    /api/menu/delete/{id}                





# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

* Production, development and CI ready
* Just 1 service by default
* Blazing-fast performance thanks to [the worker mode of FrankenPHP](https://github.com/dunglas/frankenphp/blob/main/docs/worker.md) (automatically enabled in prod mode)
* [Installation of extra Docker Compose services](docs/extra-services.md) with Symfony Flex
* Automatic HTTPS (in dev and prod)
* HTTP/3 and [Early Hints](https://symfony.com/blog/new-in-symfony-6-3-early-hints) support
* Real-time messaging thanks to a built-in [Mercure hub](https://symfony.com/doc/current/mercure.html)
* [Vulcain](https://vulcain.rocks) support
* Native [XDebug](docs/xdebug.md) integration
* Super-readable configuration

**Enjoy!**

## Docs

1. [Options available](docs/options.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Debugging with Xdebug](docs/xdebug.md)
6. [TLS Certificates](docs/tls.md)
7. [Using MySQL instead of PostgreSQL](docs/mysql.md)
8. [Using Alpine Linux instead of Debian](docs/alpine.md)
9. [Using a Makefile](docs/makefile.md)
10. [Updating the template](docs/updating.md)
11. [Troubleshooting](docs/troubleshooting.md)

## License

Symfony Docker is available under the MIT License.

## Credits

Created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
