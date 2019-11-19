# DDD Solution for Mars Rover Challenge

## Requrements

* php >= 7.2
* composer
* composer dependencies from composer.json:

```json
    "require": {
        "php": ">=7.2",
        "symfony/dependency-injection": "^4.3",
        "symfony/yaml": "^4.3",
        "symfony/config": "^4.3",
        "symfony/console": "^4.3"
    },
    "require-dev": {
        "phpunit/phpunit": "^8"
    }
```

## Installation

* Clone the project or download as an archive
* Install composer dependencies - `composer install`
* Run in an interactive mode - `php ./bin/console land-rovers`
* Run with a file as a datasource - `php ./bin/console land-rovers --file=./tests/Fixtures/testdata.txt`
* Run tests - `./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/`

## Project directories

```
bin
src
    Application
        Contract
        DataTransformer
        Service
    Domain
        Model
            Contract
            Entity
            Exception
            ValueObject
    Infrastructure
        IO
        Persistence
            InMemory
        Ui
            Console
                Command
tests
    Application
    Domain
        Model
    Fixtures
    Infrastructure
        Persistence
            InMemory
    Ui
        Console
```

## Architecture (DDD and other)

* Aggregate `Rover` consists of `RoverId , Position , LandingArea` value objects.
* We can land `Rover` on a Landing Area and if the Landing was successful we can apply some Instructions to it.
* Landing can be unsuccessful if Landing Location is out of boundaries of Landing Area or if this Location already
occupied by another Rover
* If Landing was successful we can move or rotate the Rover. Before moving we exam the next Rover's location and if it
is already occupied or it is out of boundaries of Landing Area we just skip this move.
* For persistence we use `InMemoryRoverRepository` which implements `RoverRepositoryInterface`
* We communicate with Domain Model using Application Service. There are the next Services:
    * LandRoverService
    * MoveRoverService
    * LandedRoverStatusService
* There are some unit tests in `tests` folder and one functional test (`LandRoversCommandFunctionalTest.php`).
* We use Symfony DI Component as DI Container. The container configuration is in `Infrastructure/Ui/Console/services.yaml` file.
    
## TODO
* Dockerize the app
* Unit tests
* Maybe there is a lack of validation
* Try with additional UIs
* Domain Model refactoring never ends :)

